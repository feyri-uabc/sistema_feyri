import ILaboratories from "@/services/api/interfaces/ILaboratories";
import IReservations from "@/services/api/interfaces/IReservations";
import IInstructors from "@/services/api/interfaces/IInstructors";
import Cookie from "@/services/Cookie";

export default class APIServices {
    private static path: string = (process.env.NODE_ENV !== 'production' ?"http://localhost:8000/" :"/") + "api/calendar_"

    private static async GetAll<T>(_path: string, request: RequestInit = { method: "GET" }): Promise<Array<T | null>> {
        return await fetch(this.path + _path, request)
            .then(data => data.json())
            .then((result: Array<T> | []) => result)
    }

    private static async Delete<T>(_path: string, request: RequestInit = { method: "DELETE" }): Promise<string | null> {
        return await fetch(this.path + _path, request)
            .then(data => data.text())
            .then((result: string | null) => result)
    }

    private static async Create<T>(_path: string, item: any): Promise<T | null> {
        let formData: any = new FormData()
        for (let key of Object.keys(item)) formData.append(key, item[key])

        let request: RequestInit = {
            method: "POST",
            body: formData
        }

        return await fetch(this.path + _path, request)
            .then(data => data.json())
            .then((result: T | null) => result)
    }

    // --

    public static async GetAllLaboratories(): Promise<Array<ILaboratories | null>> {
        return this.GetAll<ILaboratories>("labs")
    }

    public static async DeleteLaboratory(id: number): Promise<string | null> {
        return this.Delete<ILaboratories>("labs/" + id)
    }

    public static async CreateLaboratory(lab: ILaboratories): Promise<ILaboratories | null> {
        return this.Create<ILaboratories>("labs", lab)
    }

    // --

    public static async GetAllInstructors(): Promise<Array<IInstructors | null>> {
        return this.GetAll<IInstructors>("instructors")
    }

    public static async DeleteInstructor(id: number): Promise<string | null> {
        return this.Delete<IInstructors>("instructors/" + id)
    }

    public static async CreateIInstructor(instructor: IInstructors): Promise<IInstructors | null> {
        return this.Create<IInstructors>("instructors", instructor)
    }

    // --

    public static async GetAllReservations(): Promise<Array<IReservations | null>> {
        return this.GetAll<IReservations>("reservations")
    }

    public static async DeleteReservation(id: number): Promise<string | null> {
        return this.Delete<IReservations>("reservations/" + id)
    }

    public static async CreateReservation(reservation: IReservations): Promise<IReservations | null> {
        return this.Create<IReservations>("reservations", reservation)
    }
}
