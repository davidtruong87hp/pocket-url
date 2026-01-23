export interface SummaryStats {
  totalEngagements: number
  uniqueClicks: number
  mobileClicks: number
  dateRange: {
    start: string
    end: string
  }
}

export interface TimelineStat {
  date: string
  label: string
  engagements: number
}

export interface DeviceBreakdown {
  label: string
  value: number
  percentage: number
}

export interface LocationBreakdown {
  rank: number
  location: string
  engagements: number
  percentage: number
}

export interface ReferrerBreakdown {
  label: string
  value: number
}

export interface CardStat {
  title: string
  subtitle: string
  value: string
}

export interface LinkStats {
  deviceBreakdown: DeviceBreakdown[]
  locationBreakdown: {
    cities: LocationBreakdown[]
    countries: LocationBreakdown[]
  }
  referrerBreakdown: ReferrerBreakdown[]
  stats: CardStat[]
  summary: SummaryStats
  timeline: TimelineStat[]
}
