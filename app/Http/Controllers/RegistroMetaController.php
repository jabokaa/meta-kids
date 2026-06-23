<?php

namespace App\Http\Controllers;

use App\Models\CriancaMeta;
use App\Models\RegistroMeta;
use App\Services\MetaPeriodoService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistroMetaController extends Controller
{
    private function authorizeMeta(CriancaMeta $meta): void
    {
        abort_if($meta->crianca->user_id !== Auth::id(), 403);
    }

    private function validateDate(CriancaMeta $meta, string $date): ?array
    {
        $day  = Carbon::parse($date)->startOfDay();
        $hoje = Carbon::today();

        if ($meta->bloquear_dias_anteriores) {
            if (!$day->eq($hoje)) {
                return ['error' => 'Esta meta só permite registros para hoje.'];
            }
        } elseif ($day->gt($hoje) || $day->lt($hoje->copy()->subDays(3))) {
            return ['error' => 'Apenas os últimos 3 dias são permitidos.'];
        }

        return null;
    }

    public function index(CriancaMeta $meta)
    {
        $this->authorizeMeta($meta);
        return $meta->registros()->orderBy('data')->get();
    }

    public function periodos(CriancaMeta $meta)
    {
        $this->authorizeMeta($meta);
        return $meta->periodos()->get();
    }

    public function store(Request $request, CriancaMeta $meta)
    {
        $this->authorizeMeta($meta);

        $data = $request->validate([
            'data'  => ['required', 'date'],
            'icone' => ['required', 'string', 'max:10'],
        ]);

        if ($err = $this->validateDate($meta, $data['data'])) {
            return response()->json($err, 422);
        }

        if ($meta->maximo_por_dia) {
            $count = $meta->registros()->whereDate('data', $data['data'])->count();
            if ($count >= $meta->maximo_por_dia) {
                return response()->json(['error' => 'Máximo de ícones por dia atingido.'], 422);
            }
        }

        $registro = $meta->registros()->create($data);
        MetaPeriodoService::sincronizar($meta, $data['data']);

        return response()->json($registro, 201);
    }

    public function update(Request $request, RegistroMeta $registro)
    {
        $meta = $registro->meta;
        $this->authorizeMeta($meta);

        $data = $request->validate(['data' => ['required', 'date']]);

        if ($err = $this->validateDate($meta, $data['data'])) {
            return response()->json($err, 422);
        }

        $newIso = Carbon::parse($data['data'])->toDateString();
        $oldIso = $registro->data->toDateString();

        if ($newIso !== $oldIso && $meta->maximo_por_dia) {
            $count = $meta->registros()
                ->whereDate('data', $newIso)
                ->where('id', '!=', $registro->id)
                ->count();
            if ($count >= $meta->maximo_por_dia) {
                return response()->json(['error' => 'Máximo de ícones por dia atingido.'], 422);
            }
        }

        $registro->update(['data' => $newIso]);
        MetaPeriodoService::sincronizar($meta, $newIso);
        if ($oldIso !== $newIso) {
            MetaPeriodoService::sincronizar($meta, $oldIso);
        }

        return response()->json($registro->fresh());
    }

    public function destroy(RegistroMeta $registro)
    {
        $meta = $registro->meta;
        $this->authorizeMeta($meta);

        $date = $registro->data->toDateString();
        $registro->delete();
        MetaPeriodoService::sincronizar($meta, $date);

        return response()->noContent();
    }
}
