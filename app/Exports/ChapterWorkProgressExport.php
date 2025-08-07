<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ChapterWorkProgressExport implements FromView
{
    protected $chapters;
    protected $filterWeeks;

    public function __construct($chapters, $filterWeeks = [])
    {
        $this->chapters = $chapters;
        $this->filterWeeks = $filterWeeks;
    }

    public function view(): View
    {
        return view('exports.chapter-progress', [
            'chapters' => $this->chapters,
            'filterWeeks' => $this->filterWeeks,
        ]);
    }
}
