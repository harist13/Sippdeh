class Paslon {
  constructor(id, suara) {
    this.id = id;
    this.suara = suara;
  }

  // Convert Paslon instance to plain object
  toObject() {
    return { id: this.id, suara: this.suara };
  }
}

class TPS {
  constructor(id, dpt, paslonList, suara_sah, suara_tidak_sah, jumlah_pengguna_tidak_pilih, suara_masuk, partisipasi) {
    this.id = id;
    this.dpt = dpt;
    this.paslonList = paslonList;
    this.suara_sah = suara_sah;
    this.suara_tidak_sah = suara_tidak_sah;
    this.jumlah_pengguna_tidak_pilih = jumlah_pengguna_tidak_pilih;
    this.suara_masuk = suara_masuk;
    this.partisipasi = partisipasi;
  }

  addPaslon(paslon) {
    this.paslonList.push(paslon);
  }

  // Convert TPS instance to plain object for saving in localStorage
  toObject() {
    return {
      id: this.id,
      dpt: this.dpt,
      paslonList: this.paslonList.map(p => p instanceof Paslon ? p.toObject() : p), // Convert each Paslon to object
      suara_sah: this.suara_sah,
      suara_tidak_sah: this.suara_tidak_sah,
      jumlah_pengguna_tidak_pilih: this.jumlah_pengguna_tidak_pilih,
      suara_masuk: this.suara_masuk,
      partisipasi: this.partisipasi
    };
  }

  // Static method to retrieve all TPS data from localStorage
  static getAllTPS() {
    try {
      const data = JSON.parse(localStorage.getItem('tps_data')) || [];
      return Array.isArray(data) ? data.map(item => TPS.fromObject(item)) : [];
    } catch {
      return [];
    }
  }

  // Method to save the current TPS instance to localStorage
  save() {
    if (TPS.exists(this.id)) {
      return;
    }
    
    const data = TPS.getAllTPS();
    data.push(this);
    localStorage.setItem('tps_data', JSON.stringify(data.map(tps => tps.toObject())));
  }

  // Static method to create a TPS instance from plain object
  static fromObject(obj) {
    return new TPS(
      obj.id,
      obj.dpt,
      obj.paslonList,
      obj.suara_sah,
      obj.suara_tidak_sah,
      obj.jumlah_pengguna_tidak_pilih,
      obj.suara_masuk,
      obj.partisipasi
    );
  }

  // Static method to update an existing TPS by `id`
  static update(id, updatedData) {
    const data = TPS.getAllTPS();
    const index = data.findIndex(item => item.id === id);

    if (index !== -1) {
      Object.assign(data[index], updatedData);
      localStorage.setItem('tps_data', JSON.stringify(data.map(tps => tps.toObject())));
    } else {
      console.error("TPS with specified ID not found");
    }
  }

  // Static method to delete a TPS by `id`
  static delete(id) {
    const data = TPS.getAllTPS();
    const updatedData = data.filter(item => item.id !== id);
    localStorage.setItem('tps_data', JSON.stringify(updatedData.map(tps => tps.toObject())));
  }

  // Static method to retrieve a TPS by `id`
  static getById(id) {
    const data = TPS.getAllTPS();
    return data.find(item => item.id === id) || null;
  }

  // Static method to check if a TPS with the given `id` exists
  static exists(id) {
    return TPS.getAllTPS().some(item => item.id === id);
  }
}