<?php

namespace App\Services;

use App\Models\CriancaMeta;
use App\Models\MetaEmAndamento;
use Carbon\Carbon;

class MetaPeriodoService
{
    /**
     * Gera (ou regera) todos os períodos de uma meta.
     * Chamado ao criar ou editar a meta.
     * Preserva contadores recalculando a partir dos registros existentes.
     */
    public static function gerar(CriancaMeta $meta): void
    {
        $inicio = Carbon::parse($meta->data_inicio)->startOfDay();
        $fim    = $meta->data_fim
            ? Carbon::parse($meta->data_fim)->startOfDay()
            : $inicio->copy()->addYears(3);

        $len = $meta->tipo === 'semanal' ? 7 : 30;

        // Agrupa registros existentes por número de período
        $regsPorPeriodo = $meta->registros()
            ->get()
            ->groupBy(function ($r) use ($inicio, $len) {
                $dia  = Carbon::parse($r->data)->startOfDay();
                $days = max(0, (int) $inicio->diffInDays($dia));
                return (int) floor($days / $len);
            });

        $meta->periodos()->delete();

        $inserts   = [];
        $pStart    = $inicio->copy();
        $periodNum = 0;
        $now       = now()->toDateTimeString();

        while ($pStart->lte($fim)) {
            $pEnd  = $pStart->copy()->addDays($len - 1);
            if ($pEnd->gt($fim)) {
                $pEnd = $fim->copy();
            }

            $count = ($regsPorPeriodo[$periodNum] ?? collect())->count();

            $inserts[] = [
                'meta_id'     => $meta->id,
                'data_inicio' => $pStart->toDateString(),
                'data_fim'    => $pEnd->toDateString(),
                'contador'    => $count,
                'concluida'   => $count >= $meta->valor_meta,
                'created_at'  => $now,
                'updated_at'  => $now,
            ];

            $pStart->addDays($len);
            $periodNum++;
        }

        if ($inserts) {
            MetaEmAndamento::insert($inserts);
        }
    }

    /**
     * Recalcula o contador do período que contém $date.
     * Chamado ao criar/mover/deletar um registro.
     */
    public static function sincronizar(CriancaMeta $meta, string $date): void
    {
        $inicio = Carbon::parse($meta->data_inicio)->startOfDay();
        $dia    = Carbon::parse($date)->startOfDay();
        $len    = $meta->tipo === 'semanal' ? 7 : 30;
        $num    = (int) floor(max(0, $inicio->diffInDays($dia)) / $len);

        $pStart = $inicio->copy()->addDays($num * $len)->toDateString();
        $pEnd   = Carbon::parse($pStart)->addDays($len - 1)->toDateString();

        $count = $meta->registros()
            ->whereBetween('data', [$pStart, $pEnd])
            ->count();

        MetaEmAndamento::where('meta_id', $meta->id)
            ->where('data_inicio', $pStart)
            ->update([
                'contador'   => $count,
                'concluida'  => $count >= $meta->valor_meta,
                'updated_at' => now(),
            ]);
    }

    /**
     * Retorna quantos períodos seriam gerados (para preview/validação).
     */
    public static function contarPeriodos(CriancaMeta $meta): int
    {
        $inicio = Carbon::parse($meta->data_inicio)->startOfDay();
        $fim    = $meta->data_fim
            ? Carbon::parse($meta->data_fim)->startOfDay()
            : $inicio->copy()->addYears(3);
        $len    = $meta->tipo === 'semanal' ? 7 : 30;

        return (int) ceil($inicio->diffInDays($fim) / $len) + 1;
    }
}
