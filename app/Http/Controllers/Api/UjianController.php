<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Ujian, Soal, Jawaban, HasilUjian, User};

class UjianController extends Controller
{
    public function belum_dikerjakan($user_id){
        $ujianIds = HasilUjian::where('user_id', $user_id)->pluck('ujian_id')->toArray();

        // Filter ujian yang belum ada di hasil_ujian
        $ujians = Ujian::with(['kelas'])->withCount('soals')
                        ->whereNotIn('id', $ujianIds)
                        ->whereDate('tanggal_ujian', date('Y-m-d')) // Menyaring ujian yang sudah ada di hasil_ujian
                        ->get();

        return [
            'data' => $ujians
        ];
    }
    public function sudah_dikerjakan($user_id){
        // Ambil semua ujian_id yang ada di tabel hasil_ujian untuk user tertentu
        $ujianIds = HasilUjian::where('user_id', $user_id)->pluck('ujian_id')->toArray();

        // Filter ujian yang sudah ada di hasil_ujian (sudah dikerjakan)
        $ujians = Ujian::with(['kelas'])->withCount('soals')
                        ->whereIn('id', $ujianIds) // Menyaring ujian yang sudah ada di hasil_ujian
                        ->get();

        return [
            'data' => $ujians
        ];
    }
    public function show($id){
        $ujian = Ujian::with(['kelas'])->withCount('soals')->whereId($id)->first();
        return [
            'data'  => $ujian
        ];
    }
    public function cekToken($id, $token, $user_id){
        $ujian = Ujian::find($id);
        if($token == $ujian->token){
            $soals = Soal::select(['id'])->whereUjianId($id)->get();

            $countJawaban = Jawaban::whereUjianId($id)->whereUserId($user_id)->count();

            if($countJawaban > 0){
                return [
                    'token'         => $ujian->token,
                    'ujian_id'      => $id,
                    'waktu_ujian'   => $ujian->waktu
                ];
            }else{
                for($i=0;$i<$soals->count();$i++){
                    Jawaban::create([
                        'ujian_id'      => $id,
                        'soal_id'       => $soals[$i]->id,
                        'user_id'       => $user_id,
                        'jawaban'       => ''
                    ]);
                }
                return [
                    'token'         => $ujian->token,
                    'ujian_id'      => $id,
                    'waktu_ujian'   => $ujian->waktu
                ];
            }

        }else{
            return [
                'message'   => 'Salah',
                'user_id'   => $user_id
            ];
        }
    }

    public function selesai(Request $req){
        // Ambil data soal beserta kunci dan bobotnya
        $getSoals = Soal::whereUjianId($req->ujian_id)
        ->get(['id', 'kunci', 'bobot'])  // Ambil id, kunci, dan bobot
        ->keyBy('id');  // Menggunakan id soal sebagai key untuk memudahkan pencarian
        
        $user = User::find($req->user_id);

        // Ambil jawaban pengguna berdasarkan soal_id
        $getJawaban = Jawaban::whereUjianId($req->ujian_id)
            ->whereUserId($req->user_id)
            ->get(['soal_id', 'jawaban'])  // Ambil soal_id dan jawaban
            ->keyBy('soal_id');  // Menggunakan soal_id sebagai key untuk memudahkan pencarian

        $correctAnswers = 0;
        $incorrectAnswers = 0;
        $totalScore = 0;  // Untuk menyimpan skor total yang dihitung berdasarkan bobot soal

        // Periksa setiap jawaban yang diberikan pengguna
        foreach ($getJawaban as $soalId => $jawaban) {
            if (isset($getSoals[$soalId])) {
                // Ambil kunci dan bobot soal berdasarkan soalId
                $kunci = $getSoals[$soalId]->kunci;
                $bobot = $getSoals[$soalId]->bobot;

                // Bandingkan jawaban pengguna dengan kunci soal
                if ($jawaban->jawaban == $kunci) {
                $correctAnswers++;
                $totalScore += $bobot;  // Tambahkan bobot soal ke total skor jika jawaban benar
                } else {
                $incorrectAnswers++;
                }
            }
        }

        if($getJawaban->count() > 0){
            $insert = HasilUjian::create([
                'ujian_id'  => $req->ujian_id,
                'user_id'   => $req->user_id,
                'kelas_id'  => $user->kelas_id,
                'nilai'     => $totalScore,
                'benar'     => $correctAnswers,
                'salah'     => $incorrectAnswers,
            ]);
    
            if($insert){
                return [
                    'message'       => 'Ujian telah disimpan, dan jawaban telah dikoreksi',
                    'statusCode'    => 200
                ];
            }else{
                return [
                    'message'       => 'Ujian telah disimpan, dan jawaban gagal dikoreksi',
                    'statusCode'    => 500
                ];
            }
        }else{
            return [
                'message'       => 'Upss ada yang tidak beres',
                'statusCode'    => 500
            ];
        }

    }

    public function hasil(Request $req){
        $hasil = HasilUjian::whereUjianId($req->ujian_id)->whereUserId($req->user_id)->first();

        return [
            'data'  => $hasil
        ];
    }
}
