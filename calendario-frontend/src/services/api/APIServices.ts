import ILaboratories from "@/services/api/interfaces/ILaboratories";
import IReservations from "@/services/api/interfaces/IReservations";
import IInstructors from "@/services/api/interfaces/IInstructors";
import Cookie from "@/services/Cookie";
import IGroups from "@/services/api/interfaces/Groups";
import ICourses from "@/services/api/interfaces/Courses";

export default class APIServices {
    private static path: string = (process.env.NODE_ENV !== 'production' ?"http://localhost:8000/" :"https://soporte.feyri.mx/") + "api/calendar_"

    private static async GetAll<T>(_path: string, request: RequestInit = { method: "GET" }): Promise<Array<T | null>> {
        return await fetch(this.path + _path, request)
            .then(data => data.json())
            .then((result: Array<T> | []) => result)
    }

    private static async Delete<T>(_path: string, request: RequestInit = { method: "DELETE" }, multiple: boolean = false): Promise<string | null> {
        return await fetch(this.path + _path, request)
            .then(data => data.text())
            .then((result: string | null) => result)
    }

    private static async Update<T>(_path: string, item: any): Promise<string | null> {
        let queries = "?"
        for (let key of Object.keys(item)) if(key != "id") queries += key + "=" + item[key] + "&"

        let request: RequestInit = {
            method: "PUT",
            redirect: 'follow'
        }

        return await fetch(this.path + _path + queries, request)
            .then(data => data.text())
            .then((result: string | null) => result)
    }

    private static async Create<T>(_path: string, item: any, multiple: boolean = false): Promise<T | null> {
        let formData: any = new FormData()
        if (!multiple) {
            for (let key of Object.keys(item)) {
                formData.append(key, item[key])
            }
        } else {
            formData.append("multiple", multiple)
            for (let key of Object.keys(item)) {
                formData.append(key, JSON.stringify(item[key]))
            }
        }

        let request: RequestInit = {
            method: "POST",
            body: formData
        }
        console.log(this.path + _path)
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

    public static async UpdateLaboratory(lab: ILaboratories): Promise<string | null> {
        return this.Update<ILaboratories>("labs/" + lab.id, lab)
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

    public static async UpdateInstructor(instructors: IInstructors): Promise<string | null> {
        return this.Update<IInstructors>("instructors/" + instructors.id, instructors)
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

    public static async CreateReservationMultiple(reservation: IReservations[]): Promise<IReservations | null> {
        return this.Create<IReservations>("reservations", {reservations: reservation}, true)
    }

    // --

    public static async GetAllGroups(): Promise<Array<IGroups | null>> {
        return this.GetAll<IGroups>("groups")
    }

    public static async DeleteGroup(id: number): Promise<string | null> {
        return this.Delete<IGroups>("groups/" + id)
    }

    public static async UpdateGroups(group: IGroups): Promise<string | null> {
        return this.Update<IGroups>("groups/" + group.id, group)
    }

    public static async CreateGroup(group: IGroups): Promise<IGroups | null> {
        return this.Create<IGroups>("groups", group)
    }

    // --

    public static async GetAllCourses(): Promise<Array<ICourses | null>> {
        return this.GetAll<ICourses>("courses")
    }

    public static async DeleteCourse(id: number): Promise<string | null> {
        return this.Delete<ICourses>("courses/" + id)
    }

    public static async UpdateCourses(course: ICourses): Promise<string | null> {
        return this.Update<ICourses>("courses/" + course.id, course)
    }

    public static async CreateCourse(course: ICourses): Promise<ICourses | null> {
        return this.Create<ICourses>("courses", course)
    }
}
