export default interface IReservations {
    id?: number
    instructor_id: number
    group_id: number
    course_id: number
    lab_id: number
    select_year: number
    select_month: number
    select_day: number
    select_hour: number
    tipo: string
    grouping: number
}
