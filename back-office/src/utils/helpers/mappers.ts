import type { Salon } from '@/types/salons'
import type { Service } from '@/types/services'
import type { Resource } from '@/types/resources'

const mapNumberToDayOfWeek = (dayOfWeek: number) => {
  switch (dayOfWeek) {
    case 1:
      return 'Segunda-feira'
    case 2:
      return 'Terça-feira'
    case 3:
      return 'Quarta-feira'
    case 4:
      return 'Quinta-feira'
    case 5:
      return 'Sexta-feira'
    case 6:
      return 'Sábado'
    case 0:
      return 'Domingo'
    default:
      return ''
  }
}

const mapNumberToDayOfWeekShort = (dayOfWeek: number) => {
  switch (dayOfWeek) {
    case 1:
      return 'Seg'
    case 2:
      return 'Ter'
    case 3:
      return 'Qua'
    case 4:
      return 'Qui'
    case 5:
      return 'Sex'
    case 6:
      return 'Sáb'
    case 0:
      return 'Dom'
    default:
      return ''
  }
}

const mapDayOfWeekToNumber = (dayOfWeek: string) => {
  switch (dayOfWeek) {
    case 'Segunda-feira':
      return 1
    case 'Terça-feira':
      return 2
    case 'Quarta-feira':
      return 3
    case 'Quinta-feira':
      return 4
    case 'Sexta-feira':
      return 5
    case 'Sábado':
      return 6
    case 'Domingo':
      return 0
    default:
      return -1
  }
}

const mapSalonsToOptions = (salons: Salon[]) => {
  return salons.map((salon) => ({
    value: salon.id.toString(),
    label: salon.name,
  }))
}

const mapResourceTypeValueToLabel = (resourceType: string) => {
  switch (resourceType) {
    case 'employee':
      return 'Empregado'
    case 'machine':
      return 'Máquina'
    default:
      return ''
  }
}

const mapServicesToOptions = (services: Service[]) => {
  return services.map((service) => ({
    value: service.id,
    label: service.name,
  }))
}

const mapResourcesToOptions = (resources: Resource[]) => {
  return resources.map((resource) => ({
    value: resource.id,
    label: resource.name,
  }))
}

export default {
  mapNumberToDayOfWeek,
  mapNumberToDayOfWeekShort,
  mapDayOfWeekToNumber,
  mapSalonsToOptions,
  mapResourceTypeValueToLabel,
  mapServicesToOptions,
  mapResourcesToOptions,
}
