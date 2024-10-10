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

     .action-column {
        width: 60px;
        text-align: center;
    }
    .action-icon {
        color: #3560a0;
        font-size: 18px;
        cursor: pointer;
        transition: color 0.3s ease;
    }
    .action-icon:hover {
        color: #1e3a8a;
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
        padding-bottom: 15px;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1200px;
    }
    .table th {
        background-color: #3560a0;
        color: white;
        text-align: left;
        padding: 15px;
        font-weight: 600;
        white-space: nowrap;
    }
    .table td {
        padding: 15px;
        border-bottom: 1px solid #e6e6e6;
        white-space: nowrap;
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

    /* Custom scrollbar for table container */
    .table-container::-webkit-scrollbar {
        height: 8px;
    }
    .table-container::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 10px;
    }
    .table-container::-webkit-scrollbar-thumb:hover {
        background-color: #888;
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
                    Total Suara
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
                        <th>KECAMATAN</th>
                        <th>DPT</th>
                        <th>ANDI HARUN/ SAEFUDDIN ZUHRI</th>
                        <th>SUARA SAH</th>
                        <th>SUARA TDK SAH</th>
                        <th>JML PENG HAK PILIH</th>
                        <th>JML PENG TDK PILIH</th>
                        <th>PARTISIPASI</th>
                        <th class="action-column">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-100 text-center">
                    <tr>
                        <td>01</td>
                        <td>Kecamatan A</td>
                        <td>10000</td>
                        <td>5000</td>
                        <td>9500</td>
                        <td>500</td>
                        <td>10000</td>
                        <td>0</td>
                        <td>100%</td>
                        <td class="action-column">
                            <i class="fas fa-pen action-icon"></i>
                        </td>
                    </tr>
                    <tr>
                        <td>02</td>
                        <td>Kecamatan B</td>
                        <td>15000</td>
                        <td>7500</td>
                        <td>14000</td>
                        <td>1000</td>
                        <td>15000</td>
                        <td>1000</td>
                        <td>93.33%</td>
                        <td class="action-column">
                            <i class="fas fa-pen action-icon"></i>
                        </td>
                    </tr>
                    <tr>
                        <td>03</td>
                        <td>Kecamatan C</td>
                        <td>12000</td>
                        <td>6000</td>
                        <td>11500</td>
                        <td>500</td>
                        <td>12000</td>
                        <td>500</td>
                        <td>95.83%</td>
                        <td class="action-column">
                            <i class="fas fa-pen action-icon"></i>
                        </td>
                    </tr>
                    <!-- Add more rows as needed -->
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
