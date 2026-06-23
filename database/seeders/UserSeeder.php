<?php

namespace Database\Seeders;

use App\Models\Crianca;
use App\Models\CriancaMeta;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'teste@metakids.com'],
            ['name' => 'João Teste', 'password' => Hash::make('password')]
        );

        $user->criancas()->delete();

        $ana = Crianca::create([
            'user_id'         => $user->id,
            'nome'            => 'Ana',
            'data_nascimento' => '2018-03-15',
            'imagem'          => null,
            'estilo'          => 1,
        ]);

        $pedro = Crianca::create([
            'user_id'         => $user->id,
            'nome'            => 'Pedro',
            'data_nascimento' => '2020-07-22',
            'imagem'          => null,
            'estilo'          => 3,
        ]);

        CriancaMeta::create([
            'crianca_id'     => $ana->id,
            'descricao'      => 'Leitura diária',
            'metas'          => 'Ler pelo menos 15 minutos por dia',
            'data_inicio'    => '2026-06-01',
            'data_fim'       => '2026-08-31',
            'tipo'           => 'semanal',
            'valor_meta'     => 5,
            'maximo_por_dia' => 2,
        ]);

        CriancaMeta::create([
            'crianca_id'     => $pedro->id,
            'descricao'      => 'Arrumar o quarto',
            'metas'          => 'Arrumar o quarto todos os dias',
            'data_inicio'    => '2026-06-01',
            'data_fim'       => '2026-08-31',
            'tipo'           => 'semanal',
            'valor_meta'     => 7,
            'maximo_por_dia' => 3,
        ]);
    }
}
