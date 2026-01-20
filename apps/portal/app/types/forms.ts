export interface BaseInputProps {
  name: string
  modelValue?: string | number
  type?: 'text' | 'email' | 'password' | 'number' | 'tel' | 'url'
  label?: string
  placeholder?: string
  disabled?: boolean
  readonly?: boolean
  required?: boolean
  hint?: string
  size?: 'sm' | 'md' | 'lg'
  autocomplete?: string
  class?: string
  id?: string
  rules?: any
}

export interface PasswordInputProps extends Omit<BaseInputProps, 'type'> {
  showToggle?: boolean
  modelValue?: string
}
