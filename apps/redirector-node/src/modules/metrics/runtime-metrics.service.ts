/* eslint-disable @typescript-eslint/no-unsafe-assignment, @typescript-eslint/no-unsafe-call, @typescript-eslint/no-unsafe-argument, @typescript-eslint/no-unsafe-member-access */

import { Injectable, OnModuleInit } from '@nestjs/common';
import { Meter } from '@opentelemetry/api';
import { getMeter, MeterCategory } from './meter-registry';

@Injectable()
export class RuntimeMetricsService implements OnModuleInit {
  private meter: Meter;

  onModuleInit() {
    this.meter = getMeter(MeterCategory.RUNTIME);

    this.setupMemoryMetrics();
    this.setupProcessMetrics();
  }

  private setupMemoryMetrics() {
    const heapUsed = this.meter.createObservableGauge(
      'nodejs.memory.heap.used',
      {
        description: 'NodeJS heap memory used in bytes',
        unit: 'bytes',
      },
    );

    heapUsed.addCallback((result) => {
      result.observe(process.memoryUsage().heapUsed);
    });

    const heapTotal = this.meter.createObservableGauge(
      'nodejs.memory.heap.total',
      {
        description: 'NodeJS heap memory in bytes',
        unit: 'bytes',
      },
    );

    heapTotal.addCallback((result) => {
      result.observe(process.memoryUsage().heapTotal);
    });

    const rss = this.meter.createObservableGauge('nodejs.memory.rss', {
      description: 'Resident set size in bytes',
      unit: 'bytes',
    });

    rss.addCallback((result) => {
      result.observe(process.memoryUsage().rss);
    });

    const external = this.meter.createObservableGauge(
      'nodejs.memory.external',
      {
        description: 'External memory in bytes',
        unit: 'bytes',
      },
    );

    external.addCallback((result) => {
      result.observe(process.memoryUsage().external);
    });

    const arrayBuffers = this.meter.createObservableGauge(
      'nodejs.memory.array_buffers',
      {
        description: 'Array buffers memory in bytes',
        unit: 'bytes',
      },
    );

    arrayBuffers.addCallback((result) => {
      result.observe(process.memoryUsage().arrayBuffers);
    });
  }

  private setupProcessMetrics() {
    let lastCpuUsage = process.cpuUsage();
    let lastCheck = Date.now();

    const cpuUsage = this.meter.createObservableGauge('nodejs.cpu.usage', {
      description: 'CPU usage percentage',
      unit: 'percent',
    });

    cpuUsage.addCallback((result) => {
      const currentCpuUsage = process.cpuUsage();
      const now = Date.now();
      const elapsedTime = now - lastCheck;

      if (elapsedTime > 0) {
        const userDiff = currentCpuUsage.user - lastCpuUsage.user;
        const systemDiff = currentCpuUsage.system - lastCpuUsage.system;
        const totalDiff = userDiff + systemDiff;

        const cpuPercent = (totalDiff / (elapsedTime * 1000)) * 100;

        result.observe(cpuPercent);
      }

      lastCheck = now;
      lastCpuUsage = currentCpuUsage;
    });

    const activeHandles = this.meter.createObservableGauge(
      'nodejs.handles.active',
      {
        description: 'Number of active handles',
      },
    );

    activeHandles.addCallback((result) => {
      // @ts-expect-error - _getActiveHandles is not in types but exists
      const handles = process._getActiveHandles?.() || [];
      result.observe(handles.length);
    });

    const activeRequests = this.meter.createObservableGauge(
      'nodejs.requests.active',
      {
        description: 'Number of active requests',
      },
    );

    activeRequests.addCallback((result) => {
      // @ts-expect-error - _getActiveRequests is not in types but exists
      const requests = process._getActiveRequests?.() || [];
      result.observe(requests.length);
    });

    const uptime = this.meter.createObservableGauge('nodejs.process.uptime', {
      description: 'Process uptime in seconds',
      unit: 's',
    });

    uptime.addCallback((result) => {
      result.observe(process.uptime());
    });
  }
}
