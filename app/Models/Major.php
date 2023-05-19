<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    use HasFactory;

    public static $majors_enum = [
        'angol/anglisztika',
        'biológia',
        'egyéb',
        'fizika',
        'földrajz',
        'földtudomány',
        'gazdálkodás és menedzsment',
        'germanisztika (holland)',
        'germanisztika (német)',
        'germanisztika (skandinavisztika)',
        'keleti nyelvek és kultúrák (arab)',
        'keleti nyelvek és kultúrák (hebraisztika)',
        'keleti nyelvek és kultúrák (indológia)',
        'keleti nyelvek és kultúrák (iranisztika)',
        'keleti nyelvek és kultúrák (japán)',
        'keleti nyelvek és kultúrák (koreai)',
        'keleti nyelvek és kultúrák (kínai)',
        'keleti nyelvek és kultúrák (mongol)',
        'keleti nyelvek és kultúrák (tibeti)',
        'keleti nyelvek és kultúrák (török)',
        'keleti nyelvek és kultúrák (újgörög)',
        'kereskedelem és marketing',
        'kommunikáció-és médiatudomány',
        'kémia',
        'könyvtár/(informatikus)-könyvtáros',
        'környezettan',
        'magyar',
        'matematika',
        'művészettörténet',
        'nemzetközi gazdálkodás',
        'nemzetközi tanulmányok',
        'népművelés',
        'néprajz',
        'pedagógia',
        'programtervező informatikus/programozó matematikus',
        'pszichológia',
        'pénzügy és számvitel',
        'régészet',
        'szabad bölcsészet - esztétika',
        'szabad bölcsészet - film',
        'szabad bölcsészet - filozófia',
        'szabad bölcsészet - művészettörténet',
        'szlavisztika (bolgár)',
        'szlavisztika (cseh)',
        'szlavisztika (horvát)',
        'szlavisztika (lengyel)',
        'szlavisztika (orosz)',
        'szlavisztika (szerb)',
        'szlavisztika (szlovák)',
        'szlavisztika (szlovén)',
        'szlavisztika (ukrán)',
        'szociológia',
        'szociális munka',
        'tudományos szocializmus',
        'történelem',
        'zenekultúra',
        'ókori nyelvek és kultúrák (asszirológia)',
        'ókori nyelvek és kultúrák (egyiptológia)',
        'ókori nyelvek és kultúrák (klasszika-filológia, latin, ógörög)',
        'újlatin nyelvek és kultúrák (francia)',
        'újlatin nyelvek és kultúrák (olasz)',
        'újlatin nyelvek és kultúrák (portugál)',
        'újlatin nyelvek és kultúrák (román)',
        'újlatin nyelvek és kultúrák (spanyol)',
];

    public function alumni()
    {
        return $this->belongsToMany(Alumnus::class)->withTimestamps();
    }
}
