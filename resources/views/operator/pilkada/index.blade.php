@include('operator.layout.header')
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f9fafc;
    }
    .container {
        max-width: 1199px;
        margin: 0 auto;
        padding: 20px;
    }
    .card {
        background-color: white;
        border-radius: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }
    .filters {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-bottom: 20px;
    }
    .filter-left, .filter-right {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .filter-item {
        background-color: #eceff5;
        border-radius: 8px;
        padding: 10px 15px;
        display: flex;
        align-items: center;
        font-size: 14px;
        color: #344054;
        flex-grow: 1;
    }
    .filter-item i {
        margin-right: 8px;
    }
    .dropdown {
        position: relative;
        display: inline-block;
        flex-grow: 1;
    }
    .dropbtn {
        background-color: #eceff5;
        color: #344054;
        padding: 10px 15px;
        font-size: 14px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-radius: 8px;
        width: 100%;
    }
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 200px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
        border-radius: 8px;
        overflow: hidden;
    }
    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }
    .dropdown-content a:hover {
        background-color: #f1f1f1;
    }
    .dropdown:hover .dropdown-content {
        display: block;
    }
    .dropdown:hover .dropbtn {
        background-color: #e2e6ef;
    }
    .fa-chevron-down {
        margin-left: 10px;
    }
    .search-input {
        border: none;
        background: transparent;
        outline: none;
        width: 100%;
    }
    .table-container {
        overflow-x: auto;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        min-width: 600px;
    }
    .table th {
        background-color: #3560a0;
        color: white;
        text-align: left;
        padding: 15px;
        font-weight: 600;
    }
    .table td {
        padding: 15px;
        border-bottom: 1px solid #e6e6e6;
    }
    .btn {
        background-color: #3560a0;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 6px 12px;
        cursor: pointer;
        font-weight: 600;
        width: 80px;
        text-align: center;
    }
    .pagination {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
        margin-top: 20px;
    }
    .pagination-buttons {
        display: flex;
        gap: 10px;
    }
    .pagination-button {
        background-color: transparent;
        border: none;
        color: #6A6A6A;
        cursor: pointer;
        font-size: 13px;
    }
    .pagination-button.active {
        background-color: #0086F9;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
    }
    .action-column {
        width: 100px;
        text-align: center;
    }

    @media (min-width: 768px) {
        .filters {
            flex-direction: row;
            justify-content: space-between;
        }
        .filter-right {
            justify-content: flex-end;
        }
        .pagination {
            flex-direction: row;
            justify-content: space-between;
        }
    }
</style>

<div class="container">
    <div class="card">
        <div class="filters">
            <div class="filter-left">
                <div class="">
                    <i class="fas fa-users"></i>
                    Pilih Paslon
                </div>
            </div>
            <div class="filter-right">
                <div class="dropdown">
                    <button class="dropbtn">Pilih Kab/Kota <i class="fas fa-chevron-down"></i></button>
                    <div class="dropdown-content">
                        <a href="#">Samarinda</a>
                        <a href="#">Balikpapan</a>
                        <a href="#">Bontang</a>
                        <a href="#">Kutai Kartanegara</a>
                        <a href="#">Kutai Timur</a>
                        <a href="#">Kutai Barat</a>
                        <a href="#">Berau</a>
                        <a href="#">Paser</a>
                        <a href="#">Penajam Paser Utara</a>
                        <a href="#">Mahakam Ulu</a>
                    </div>
                </div>
                <div class="filter-item">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search" class="search-input">
                </div>
                <div class="filter-item">
                    <i class="fas fa-filter"></i>
                    Filter
                </div>
            </div>
        </div>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NAMA PASLON</th>
                        <th>KABUPATEN/KOTA</th>
                        <th class="action-column">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-100">
                    <tr>
                        <td>01</td>
                        <td>Andi Harun/Saefuddin Zuhri</td>
                        <td>Samarinda</td>
                        <td class="action-column">
                            <a href="{{ route('input-paslon.namapaslon') }}" class="btn">Pilih</a>
                        </td>

                    </tr>
                    <tr>
                        <td>02</td>
                        <td>Rahmad Mas'ud/Bagus Susetyo</td>
                        <td>Balikpapan</td>
                        <td class="action-column">
                            <a href="{{ route('input-paslon.namapaslon') }}" class="btn">Pilih</a>
                        </td>
                    </tr>
                    <tr>
                        <td>03</td>
                        <td>Rendi Susiswo Ismail/Eddy Sunardi Darmawan</td>
                        <td>Balikpapan</td>
                        <td class="action-column">
                            <a href="{{ route('input-paslon.namapaslon') }}" class="btn">Pilih</a>
                        </td>
                    </tr>
                    <tr>
                        <td>04</td>
                        <td>Muhammad Sa'bani/Syukri Wahid</td>
                        <td>Balikpapan</td>
                        <td class="action-column">
                            <a href="{{ route('input-paslon.namapaslon') }}" class="btn">Pilih</a>
                        </td>
                    </tr>
                    <tr>
                        <td>05</td>
                        <td>Neni Moerniaeni/Agus Haris</td>
                        <td>Bontang</td>
                        <td class="action-column">
                            <a href="{{ route('input-paslon.namapaslon') }}" class="btn">Pilih</a>
                        </td>
                    </tr>
                    <tr>
                        <td>06</td>
                        <td>Basri Rase/Chusnul Dhihin</td>
                        <td>Bontang</td>
                        <td class="action-column">
                            <a href="{{ route('input-paslon.namapaslon') }}" class="btn">Pilih</a>
                        </td>
                    </tr>
                    <tr>
                        <td>07</td>
                        <td>Najirah/Muhammad Aswar</td>
                        <td>Bontang</td>
                        <td class="action-column">
                            <a href="{{ route('input-paslon.namapaslon') }}" class="btn">Pilih</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <span>1 - 10 dari 40 tabel</span>
            <div class="pagination-buttons">
                <button class="pagination-button">Previous</button>
                <button class="pagination-button active">1</button>
                <button class="pagination-button">2</button>
                <button class="pagination-button">Next</button>
            </div>
        </div>
    </div>
</div>

@include('operator.layout.footer')