export default class LocalStorage {
    public static get(key: string): string {
        return window.localStorage.getItem(key) + ""
    }

    public static set(key: string, value: string): void {
        window.localStorage.setItem(key, value)
    }

    public static clear(): void {
        window.localStorage.clear()
    }

    public static containKey(str: string): boolean {
        let keys = Object.keys(window.localStorage)
        for (let key in keys) if ( key.includes(str) ) return true
        return false
    }
}
