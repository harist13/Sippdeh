<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait SortResumeColumns {
    public ?string $dptSort = null;
    public ?string $suaraSahSort = null;
    public ?string $suaraTidakSahSort = null;
    public ?string $suaraMasukSort = null;
    public ?string $abstainSort = null;
    public ?string $partisipasiSort = null;

    public ?int $paslonIdSort = null;
    public ?string $paslonSort = null;
    
    private function sortColumns(Builder $builder): void
    {
        if ($this->dptSort) {
            $builder->orderBy('dpt', $this->dptSort);
        }

        if ($this->suaraSahSort) {
            $builder->orderBy('suara_sah', $this->suaraSahSort);
        }

        if ($this->suaraTidakSahSort) {
            $builder->orderBy('suara_tidak_sah', $this->suaraTidakSahSort);
        }

        if ($this->suaraMasukSort) {
            $builder->orderBy('suara_masuk', $this->suaraMasukSort);
        }

        if ($this->abstainSort) {
            $builder->orderBy('abstain', $this->abstainSort);
        }

        if ($this->partisipasiSort) {
            $builder->orderBy('partisipasi', $this->partisipasiSort);
        }
    }

    public function sortDpt()
    {
        if ($this->dptSort == null) {
            $this->dptSort = 'asc';
        } else if ($this->dptSort == 'asc') {
            $this->dptSort = 'desc';
        } else if ($this->dptSort == 'desc') {
            $this->dptSort = null;
        }
    }

    public function sortSuaraSah()
    {
        if ($this->suaraSahSort == null) {
            $this->suaraSahSort = 'asc';
        } else if ($this->suaraSahSort == 'asc') {
            $this->suaraSahSort = 'desc';
        } else if ($this->suaraSahSort == 'desc') {
            $this->suaraSahSort = null;
        }
    }

    public function sortSuaraTidakSah()
    {
        if ($this->suaraTidakSahSort == null) {
            $this->suaraTidakSahSort = 'asc';
        } else if ($this->suaraTidakSahSort == 'asc') {
            $this->suaraTidakSahSort = 'desc';
        } else if ($this->suaraTidakSahSort == 'desc') {
            $this->suaraTidakSahSort = null;
        }
    }

    public function sortSuaraMasuk()
    {
        if ($this->suaraMasukSort == null) {
            $this->suaraMasukSort = 'asc';
        } else if ($this->suaraMasukSort == 'asc') {
            $this->suaraMasukSort = 'desc';
        } else if ($this->suaraMasukSort == 'desc') {
            $this->suaraMasukSort = null;
        }
    }

    public function sortAbstain()
    {
        if ($this->abstainSort == null) {
            $this->abstainSort = 'asc';
        } else if ($this->abstainSort == 'asc') {
            $this->abstainSort = 'desc';
        } else if ($this->abstainSort == 'desc') {
            $this->abstainSort = null;
        }
    }

    public function sortPartisipasi()
    {
        if ($this->partisipasiSort == null) {
            $this->partisipasiSort = 'asc';
        } else if ($this->partisipasiSort == 'asc') {
            $this->partisipasiSort = 'desc';
        } else if ($this->partisipasiSort == 'desc') {
            $this->partisipasiSort = null;
        }
    }

    public function sortPaslonById(int $paslonId)
    {
        if ($this->paslonIdSort == null) {
            $this->paslonIdSort = $paslonId;
        }

        if ($this->paslonSort == null) {
            $this->paslonSort = 'asc';
        } else if ($this->paslonSort == 'asc') {
            $this->paslonSort = 'desc';
        } else if ($this->paslonSort == 'desc') {
            $this->paslonIdSort = null;
            $this->paslonSort = null;
        }
    }

    private function sortResumeSuaraPilgubKabupatenPaslon(Builder $builder): void
    {
        if ($this->paslonIdSort != null && $this->paslonSort != null) {
            $builder
                ->selectRaw('SUM(suara_calon.suara) AS suara')
                ->leftJoin('kecamatan', 'kecamatan.kabupaten_id', '=', 'resume_suara_pilgub_kabupaten.id')
                ->leftJoin('kelurahan', 'kelurahan.kecamatan_id', '=', 'kecamatan.id')
                ->leftJoin('tps', 'tps.kelurahan_id', '=', 'kelurahan.id')
                ->leftJoin('suara_calon', function($joinBuilder) {
                    $joinBuilder
                        ->on('suara_calon.tps_id', '=', 'tps.id')
                        ->where('suara_calon.calon_id', $this->paslonIdSort);
                })
                ->orderBy('suara', $this->paslonSort)
                ->groupBy(
                    'resume_suara_pilgub_kabupaten.id',
                    'resume_suara_pilgub_kabupaten.nama',
                    'resume_suara_pilgub_kabupaten.provinsi_id',
                    'resume_suara_pilgub_kabupaten.dpt',
                    'resume_suara_pilgub_kabupaten.kotak_kosong',
                    'resume_suara_pilgub_kabupaten.suara_sah',
                    'resume_suara_pilgub_kabupaten.suara_tidak_sah',
                    'resume_suara_pilgub_kabupaten.suara_masuk',
                    'resume_suara_pilgub_kabupaten.abstain',
                    'resume_suara_pilgub_kabupaten.partisipasi'
                );
        }
    }

    private function sortResumeSuaraPilgubKecamatanPaslon(Builder $builder): void
    {
        if ($this->paslonIdSort != null && $this->paslonSort != null) {
            $builder
                ->selectRaw('SUM(suara_calon.suara) AS suara')
                ->leftJoin('kelurahan', 'kelurahan.kecamatan_id', '=', 'resume_suara_pilgub_kecamatan.id')
                ->leftJoin('tps', 'tps.kelurahan_id', '=', 'kelurahan.id')
                ->leftJoin('suara_calon', function($joinBuilder) {
                    $joinBuilder
                        ->on('suara_calon.tps_id', '=', 'tps.id')
                        ->where('suara_calon.calon_id', $this->paslonIdSort);
                })
                ->orderBy('suara', $this->paslonSort)
                ->groupBy(
                    'resume_suara_pilgub_kecamatan.id',
                    'resume_suara_pilgub_kecamatan.nama',
                    'resume_suara_pilgub_kecamatan.kabupaten_id',
                    'resume_suara_pilgub_kecamatan.dpt',
                    'resume_suara_pilgub_kecamatan.kotak_kosong',
                    'resume_suara_pilgub_kecamatan.suara_sah',
                    'resume_suara_pilgub_kecamatan.suara_tidak_sah',
                    'resume_suara_pilgub_kecamatan.suara_masuk',
                    'resume_suara_pilgub_kecamatan.abstain',
                    'resume_suara_pilgub_kecamatan.partisipasi'
                );
        }
    }

    private function sortResumeSuaraPilgubKelurahanPaslon(Builder $builder): void
    {
        if ($this->paslonIdSort != null && $this->paslonSort != null) {
            $builder
                ->selectRaw('SUM(suara_calon.suara) AS suara')
                ->leftJoin('tps', 'tps.kelurahan_id', '=', 'resume_suara_pilgub_kelurahan.id')
                ->leftJoin('suara_calon', function($joinBuilder) {
                    $joinBuilder
                        ->on('suara_calon.tps_id', '=', 'tps.id')
                        ->where('suara_calon.calon_id', $this->paslonIdSort);
                })
                ->orderBy('suara', $this->paslonSort)
                ->groupBy(
                    'resume_suara_pilgub_kelurahan.id',
                    'resume_suara_pilgub_kelurahan.nama',
                    'resume_suara_pilgub_kelurahan.kecamatan_id',
                    'resume_suara_pilgub_kelurahan.dpt',
                    'resume_suara_pilgub_kelurahan.kotak_kosong',
                    'resume_suara_pilgub_kelurahan.suara_sah',
                    'resume_suara_pilgub_kelurahan.suara_tidak_sah',
                    'resume_suara_pilgub_kelurahan.suara_masuk',
                    'resume_suara_pilgub_kelurahan.abstain',
                    'resume_suara_pilgub_kelurahan.partisipasi'
                );
        }
    }

    private function sortResumeSuaraPilgubTpsPaslon(Builder $builder): void
    {
        if ($this->paslonIdSort != null && $this->paslonSort != null) {
            $builder
                ->selectRaw('SUM(suara_calon.suara) AS suara')
                ->leftJoin('suara_calon', function($joinBuilder) {
                    $joinBuilder
                        ->on('suara_calon.tps_id', '=', 'resume_suara_pilgub_tps.id')
                        ->where('suara_calon.calon_id', $this->paslonIdSort);
                })
                ->orderBy('suara', $this->paslonSort)
                ->groupBy(
                    'resume_suara_pilgub_tps.id',
                    'resume_suara_pilgub_tps.nama',
                    'resume_suara_pilgub_tps.dpt',
                    'resume_suara_pilgub_tps.kotak_kosong',
                    'resume_suara_pilgub_tps.suara_sah',
                    'resume_suara_pilgub_tps.suara_tidak_sah',
                    'resume_suara_pilgub_tps.suara_masuk',
                    'resume_suara_pilgub_tps.abstain',
                    'resume_suara_pilgub_tps.partisipasi'
                );
        }
    }

    private function sortResumeSuaraPilwaliKabupatenPaslon(Builder $builder): void
    {
        if ($this->paslonIdSort != null && $this->paslonSort != null) {
            $builder
                ->selectRaw('SUM(suara_calon.suara) AS suara')
                ->leftJoin('kecamatan', 'kecamatan.kabupaten_id', '=', 'resume_suara_pilwali_kabupaten.id')
                ->leftJoin('kelurahan', 'kelurahan.kecamatan_id', '=', 'kecamatan.id')
                ->leftJoin('tps', 'tps.kelurahan_id', '=', 'kelurahan.id')
                ->leftJoin('suara_calon', function($joinBuilder) {
                    $joinBuilder
                        ->on('suara_calon.tps_id', '=', 'tps.id')
                        ->where('suara_calon.calon_id', $this->paslonIdSort);
                })
                ->orderBy('suara', $this->paslonSort)
                ->groupBy(
                    'resume_suara_pilwali_kabupaten.id',
                    'resume_suara_pilwali_kabupaten.nama',
                    'resume_suara_pilwali_kabupaten.provinsi_id',
                    'resume_suara_pilwali_kabupaten.dpt',
                    'resume_suara_pilwali_kabupaten.kotak_kosong',
                    'resume_suara_pilwali_kabupaten.suara_sah',
                    'resume_suara_pilwali_kabupaten.suara_tidak_sah',
                    'resume_suara_pilwali_kabupaten.suara_masuk',
                    'resume_suara_pilwali_kabupaten.abstain',
                    'resume_suara_pilwali_kabupaten.partisipasi'
                );
        }
    }

    private function sortResumeSuaraPilwaliKecamatanPaslon(Builder $builder): void
    {
        if ($this->paslonIdSort != null && $this->paslonSort != null) {
            $builder
                ->selectRaw('SUM(suara_calon.suara) AS suara')
                ->leftJoin('kelurahan', 'kelurahan.kecamatan_id', '=', 'resume_suara_pilwali_kecamatan.id')
                ->leftJoin('tps', 'tps.kelurahan_id', '=', 'kelurahan.id')
                ->leftJoin('suara_calon', function($joinBuilder) {
                    $joinBuilder
                        ->on('suara_calon.tps_id', '=', 'tps.id')
                        ->where('suara_calon.calon_id', $this->paslonIdSort);
                })
                ->orderBy('suara', $this->paslonSort)
                ->groupBy(
                    'resume_suara_pilwali_kecamatan.id',
                    'resume_suara_pilwali_kecamatan.nama',
                    'resume_suara_pilwali_kecamatan.kabupaten_id',
                    'resume_suara_pilwali_kecamatan.dpt',
                    'resume_suara_pilwali_kecamatan.kotak_kosong',
                    'resume_suara_pilwali_kecamatan.suara_sah',
                    'resume_suara_pilwali_kecamatan.suara_tidak_sah',
                    'resume_suara_pilwali_kecamatan.suara_masuk',
                    'resume_suara_pilwali_kecamatan.abstain',
                    'resume_suara_pilwali_kecamatan.partisipasi'
                );
        }
    }

    private function sortResumeSuaraPilwaliKelurahanPaslon(Builder $builder): void
    {
        if ($this->paslonIdSort != null && $this->paslonSort != null) {
            $builder
                ->selectRaw('SUM(suara_calon.suara) AS suara')
                ->leftJoin('tps', 'tps.kelurahan_id', '=', 'resume_suara_pilwali_kelurahan.id')
                ->leftJoin('suara_calon', function($joinBuilder) {
                    $joinBuilder
                        ->on('suara_calon.tps_id', '=', 'tps.id')
                        ->where('suara_calon.calon_id', $this->paslonIdSort);
                })
                ->orderBy('suara', $this->paslonSort)
                ->groupBy(
                    'resume_suara_pilwali_kelurahan.id',
                    'resume_suara_pilwali_kelurahan.nama',
                    'resume_suara_pilwali_kelurahan.kecamatan_id',
                    'resume_suara_pilwali_kelurahan.dpt',
                    'resume_suara_pilwali_kelurahan.kotak_kosong',
                    'resume_suara_pilwali_kelurahan.suara_sah',
                    'resume_suara_pilwali_kelurahan.suara_tidak_sah',
                    'resume_suara_pilwali_kelurahan.suara_masuk',
                    'resume_suara_pilwali_kelurahan.abstain',
                    'resume_suara_pilwali_kelurahan.partisipasi'
                );
        }
    }

    private function sortResumeSuaraPilwaliTpsPaslon(Builder $builder): void
    {
        if ($this->paslonIdSort != null && $this->paslonSort != null) {
            $builder
                ->selectRaw('SUM(suara_calon.suara) AS suara')
                ->leftJoin('suara_calon', function($joinBuilder) {
                    $joinBuilder
                        ->on('suara_calon.tps_id', '=', 'resume_suara_pilwali_tps.id')
                        ->where('suara_calon.calon_id', $this->paslonIdSort);
                })
                ->orderBy('suara', $this->paslonSort)
                ->groupBy(
                    'resume_suara_pilwali_tps.id',
                    'resume_suara_pilwali_tps.nama',
                    'resume_suara_pilwali_tps.dpt',
                    'resume_suara_pilwali_tps.kotak_kosong',
                    'resume_suara_pilwali_tps.suara_sah',
                    'resume_suara_pilwali_tps.suara_tidak_sah',
                    'resume_suara_pilwali_tps.suara_masuk',
                    'resume_suara_pilwali_tps.abstain',
                    'resume_suara_pilwali_tps.partisipasi'
                );
        }
    }
}