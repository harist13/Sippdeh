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
}