<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Procedure;

class ProcedureSeeder extends Seeder
{
    public function run(): void
    {
        $procedures = [
            'Incision and drainage',
            'Wound debridement',
            'Wound suturing',
            'Excision of small lumps',
            'Nail avulsion',
            'Nerve blocks',
            'Suprapubic catheter',
            'Chest tube insertion',
            'Splinting fractures',
            'Emergency laparotomy',
            'Appendicectomy',
            'Ex lap adhesiolysis',
            'Bowel resection and anastomosis',
            'Stoma formation',
            'Stoma reversal',
            'Inguinal hernia repair Herniorrhaphy',
            'Mesh Hernioplasty',
            'Herniotomy',
            'Umbilical hernia repair',
            'Epigastric hernia repair',
            'Incisional hernia repair',
            'Cholecystectomy',
            'Cholecystostomy',
            'CBD exploration',
            'Hemorrhoidectomy',
            'Lateral sphincterotomy',
            'Fistulotomy',
            'Fistulectomy',
            'Rectal biopsy',
            'Incision and drainage of breast abscess',
            'Lumpectomy',
            'Excision biopsy (breast lump)',
            'Modified radical mastectomy',
            'Thyroidectomy',
            'Parathyroidectomy',
            'Circumcision',
            'Hydrocelectomy',
            'Orchidopexy',
            'Testicular torsion exploration',
            'Venous cut-down',
            'Central venous line insertion',
            'Arteriovenous fistula creation',
            'Prostate core biopsy',
            'Breast core biopsy',
            'Prostatectomy',
            'Intussusception',
            'Shunting for priapism',
            'BSO',
            'Varicocelectomy',
        ];

        foreach ($procedures as $name) {
            Procedure::updateOrCreate(['name' => $name], ['is_active' => true]);
        }
    }
}
