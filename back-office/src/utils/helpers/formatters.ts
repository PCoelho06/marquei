const formatPrice = (price: number) => {
  return new Intl.NumberFormat('pt-PT', {
    style: 'currency',
    currency: 'EUR',
  }).format(price / 100)
}

const formatDateFromTimestamp = (timestamp: number) => {
  return new Intl.DateTimeFormat('pt-PT').format(new Date(timestamp * 1000))
}

const formatDate = (date: string) => {
  return new Intl.DateTimeFormat('pt-PT').format(new Date(date))
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
  formatPrice,
  formatDate,
  formatDuration,
  formatDateFromTimestamp,
  formatPhone,
  formatPostalCode,
  formatAddress,
}
