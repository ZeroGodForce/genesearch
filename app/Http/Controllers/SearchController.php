<?php

namespace App\Http\Controllers;

use App\Imports\VcfImport;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class SearchController extends Controller
{
    /**
     * @param string  $chrom
     * @param string  $pos
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function search(string $chrom, string $pos)
    {
        // Import the vcf file (located in /storage/app/public)
        $rows = Excel::toCollection(
            new VcfImport,
            'input_tiny.vcf',
            'public',
            \Maatwebsite\Excel\Excel::TSV
        )[0];

        // First pass: Remove info section from the top of the file
        foreach ($rows as $num => $row) {
            foreach ($row as $column => $value) {
                if (Str::startsWith($value, '#')) {
                    $rows->forget($num);
                }
            }
        }

        /** Second pass: Rearrange into array groups by chromosome for simpler searching
         * though normally I would just import the data into a database as it's far better
         * at this kind of search
         */
        $results = [];

        foreach ($rows as $val) {
            $results[$val[0]][] = $val->except(0);
        }

        // Search and return result if there is a match
        foreach ($results[$chrom] as $result) {
            if ($result[1] == $pos) {
                return view('welcome', ['match' => $result[3] ]);
            }
        }

        return view('welcome', ['match' => 'NOT FOUND']);
    }


    public function index()
    {
        return view('welcome');
    }
}
