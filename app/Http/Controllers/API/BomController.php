<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Bom;
use App\Models\BomRawMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BomController extends Controller
{

    public function saveBom(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'bom.uom' => 'nullable',
            'bom.is_active' => 'sometimes',
            'bom.quantity' => 'required',
            'bom.is_default' => 'sometimes',
            'bom.allow_alternative' => 'sometimes',
            'bom.rate_set' => 'sometimes',
            'bom.project' => 'sometimes'
        ]);
//        dd($request['rawMaterials'], $request->bom);
        try {
            DB::beginTransaction();

            $bom = Bom::create([
                'product_id' => $request->product_id,
                'uom' => $request->bom['uom'],
                'is_active' => $request->bom['is_active'] ?? 0,
                'quantity' => $request->bom['quantity'],
                'is_default' => $request->bom['is_default'] ?? 0,
                'allow_alternative' => $request->bom['allow_alternative'] ?? 0,
                'rate_set' => $request->bom['rate_set'] ?? 0,
                'project' => $request->bom['project'] ?? null,
                'items_count' => count($request['rawMaterials'])
            ]);

            $this->saveBomMaterial($bom, $request['rawMaterials']);
            DB::commit();

        } catch (\Exception $e) {
            // If an exception occurs, rollback the transaction
            DB::rollback();
            throw $e;
        }

        return response()->json(['status' => true, 'message' => "Ok"], 201);
    }

    private function saveBomMaterial($bom, mixed $rawMaterials)
    {
        foreach ($rawMaterials as $material) {
            BomRawMaterial::create([
                'bom_id' => $bom->id,
                'raw_material_id' => $material['id'],
                'quantity' => $material['qty'],
                'unit_price' => $material['price'],
                'amount' => $material['amount'],
                'user_id' => auth()->id()
            ]);
        }

        return true;

    }
}
