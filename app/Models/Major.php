<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    use HasFactory;

    public static $majors_enum = [
        'angol/anglisztika',
        'germanisztika (német)',
        'germanisztika (holland)',
        'germanisztika (skandinavisztika)',
        'könyvtár/(informatikus)-könyvtáros',
        'keleti nyelvek és kultúrák (arab)',
        'keleti nyelvek és kultúrák (hebraisztika)',
        'keleti nyelvek és kultúrák (indológia)',
        'keleti nyelvek és kultúrák (iranisztika)',
        'keleti nyelvek és kultúrák (japán)',
        'keleti nyelvek és kultúrák (kínai)',
        'keleti nyelvek és kultúrák (koreai)',
        'keleti nyelvek és kultúrák (mongol)',
        'keleti nyelvek és kultúrák (tibeti)',
        'keleti nyelvek és kultúrák (török)',
        'keleti nyelvek és kultúrák (újgörög)',
        'kommunikáció-és médiatudomány',
        'magyar',
        'művészettörténet',
        'néprajz',
        'ókori nyelvek és kultúrák (asszirológia)',
        'ókori nyelvek és kultúrák (egyiptológia)',
        'ókori nyelvek és kultúrák (klasszika-filológia, latin, ógörög)',
        'régészet',
        'szabad bölcsészet - művészettörténet',
        'szabad bölcsészet - filozófia',
        'szabad bölcsészet - esztétika',
        'szabad bölcsészet - film',
        'szlavisztika (bolgár)',
        'szlavisztika (cseh)',
        'szlavisztika (horvát)',
        'szlavisztika (lengyel)',
        'szlavisztika (orosz)',
        'szlavisztika (szerb)',
        'szlavisztika (szlovák)',
        'szlavisztika (szlovén)',
        'szlavisztika (ukrán)',
        'történelem',
        'újlatin nyelvek és kultúrák (francia)',
        'újlatin nyelvek és kultúrák (olasz)',
        'újlatin nyelvek és kultúrák (portugál)',
        'újlatin nyelvek és kultúrák (román)',
        'újlatin nyelvek és kultúrák (spanyol)',
        'zenekultúra',
        'biológia',
        'fizika',
        'földrajz',
        'földtudomány',
        'kémia',
        'környezettan',
        'matematika',
        'nemzetközi tanulmányok',
        'szociális munka',
        'szociológia',
        'programtervező informatikus/programozó matematikus',
        'gazdálkodás és menedzsment',
        'kereskedelem és marketing',
        'nemzetközi gazdálkodás',
        'pénzügy és számvitel',
        'pedagógia',
        'pszichológia',
        'népművelés',
        'tudományos szocializmus',
        'egyéb'
    ];

    public function alumni()
    {
        return $this->belongsToMany(Alumnus::class)->withTimestamps();
    }
}
