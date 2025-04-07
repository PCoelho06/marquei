const capitalize = (str: string) => {
  return str.charAt(0).toUpperCase() + str.slice(1)
}

const formatPrice = (price: number) => {
  return new Intl.NumberFormat('pt-PT', {
    style: 'currency',
    currency: 'EUR',
  }).format(price / 100)
}

const formatDate = (date: Date) => {
  const formattedDate = date.toLocaleDateString('pt-PT', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
  return formattedDate.charAt(0).toUpperCase() + formattedDate.slice(1)
}

const formatDateFromTimestamp = (timestamp: number) => {
  return new Intl.DateTimeFormat('pt-PT').format(new Date(timestamp * 1000))
}

const formatDateFromString = (date: string) => {
  return new Intl.DateTimeFormat('pt-PT').format(new Date(date))
}

const formatTime = (time: string) => {
  const [hours, minutes] = time.split(':')
  const date = new Date()
  date.setHours(parseInt(hours, 10))
  date.setMinutes(parseInt(minutes, 10))
  return new Intl.DateTimeFormat('pt-PT', {
    hour: '2-digit',
    minute: '2-digit',
    hour12: false,
  }).format(date)
}

const formatDatetime = (datetime: string) => {
  const date = new Date(datetime)

  return `${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}`
}

const formatTimeSlot = (time: Date) => {
  return {
    time,
    label: time.toLocaleTimeString('pt-PT', { hour: '2-digit', minute: '2-digit' }),
    isPassed: time.getTime() < Date.now(),
  }
}

const formatDateTimeForm = (date: Date) => {
  const options: Intl.DateTimeFormatOptions = {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    hour12: false,
  }
  return new Intl.DateTimeFormat('pt-PT', options).format(date)
}

const formatDuration = (duration: number) => {
  const hours = Math.floor(duration / 60)
  const minutes = duration % 60
  if (hours > 0) {
    let result = `${hours}h`
    if (minutes > 0) {
      result += ` ${minutes} min`
    }
    return result
  }
  return `${duration} min`
}

const formatPhone = (phone: string) => {
  const cleanedPhone = phone.replace('+351', '').trim()
  const formattedPhone = cleanedPhone.replace(/(\d{3})(\d{3})(\d{3})/, '$1 $2 $3')
  return ((formattedPhone.length > 0 ? '+351 ' : '') + formattedPhone).trim()
}

const formatPostalCode = (postalCode: string) => {
  const cleanedPostalCode = postalCode.replace('-', '').trim()
  const formattedPostalCode = cleanedPostalCode.replace(/(\d{4})(\d{3})/, '$1-$2')
  return formattedPostalCode
}

const formatAddress = (address: string, postalCode: string, city: string) => {
  return `${address}, ${formatPostalCode(postalCode)} ${city}`
}

export default {
  capitalize,
  formatPrice,
  formatDate,
  formatDateFromString,
  formatTime,
  formatDatetime,
  formatDateTimeForm,
  formatTimeSlot,
  formatDuration,
  formatDateFromTimestamp,
  formatPhone,
  formatPostalCode,
  formatAddress,
}
