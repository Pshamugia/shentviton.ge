class MemStorage {
    storage = new Map();

    setItem(k, v) {
        this.storage.set(k, v);
    }

    getItem(k) {
        return this.storage.get(k);
    }

    clearMatching(predicate) {
        for (const key of this.storage.keys()) {
            if (predicate(key)) {
                this.storage.delete(key);
            }
        }

        console.log("this storage after cleanup:", this.storage);
    }
}

export default MemStorage;
