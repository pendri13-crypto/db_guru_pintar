<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'classes';
    protected $guarded = [];

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'class_id');
    }

    public function journals()
    {
        return $this->hasMany(TeachingJournal::class, 'class_id');
    }

    /**
     * Dapatkan daftar kelas terurut secara natural (Tingkat VII, VIII, IX, dst) beserta jumlah siswa
     */
    public static function getSortedClasses($onlyWithStudents = false)
    {
        $query = static::withCount('students');
        if ($onlyWithStudents) {
            $query->has('students');
        }

        return $query->get()->sort(function ($a, $b) {
            $parseClass = function ($name) {
                $name = strtoupper(trim($name));
                $grade = 99;
                $section = $name;

                // Match Roman Numerals: VIII, VII, XII, XI, IX, IV, VI, V, X, I-III
                if (preg_match('/^(VIII|VII|XII|XI|IX|IV|VI|V|X|I{1,3})[\s\-_]*([A-Z0-9]*)$/i', $name, $m)) {
                    $romanMap = [
                        'I' => 1, 'II' => 2, 'III' => 3, 'IV' => 4, 'V' => 5,
                        'VI' => 6, 'VII' => 7, 'VIII' => 8, 'IX' => 9, 'X' => 10,
                        'XI' => 11, 'XII' => 12
                    ];
                    $grade = $romanMap[strtoupper($m[1])] ?? 99;
                    $section = $m[2] ?? '';
                } elseif (preg_match('/^(\d+)[\s\-_]*([A-Z0-9]*)$/i', $name, $m)) {
                    $grade = (int) $m[1];
                    $section = $m[2] ?? '';
                }
                return [$grade, $section, $name];
            };

            [$gradeA, $secA] = $parseClass($a->name);
            [$gradeB, $secB] = $parseClass($b->name);

            if ($gradeA !== $gradeB) {
                return $gradeA <=> $gradeB;
            }
            return strcmp($secA, $secB);
        })->values();
    }
}
