export default class Cookie {
    public static get(key: string): string {
        return this.parse()[key]
    }

    public static containKey(str: string): boolean {
        return document.cookie.includes(str)
    }

    private static parse() {
        let fields = document.cookie.split(";")
        let data: any = []

        for (let field of fields) {
            let keyValue = field.split("=")
            data[keyValue[0]] = keyValue[1]
        }

        return data
    }
}
