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
  return `${duration} min`
}

export default { formatPrice, formatDate, formatDuration, formatDateFromTimestamp }
