<?php

namespace App\Http\Controllers;

use App\Exports\PedidoGeralReport;
use App\Http\Controllers\Controller;
use App\PedidoGeral;
use App\Produto;
use App\Supplier;
use App\User;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;



class PedidosReportsV2Controller extends Controller
{

    public function index(Request $request)
    {
        $produtos = Produto::orderBy('nome')->get();
        $utilizadores = User::orderBy('name')->get();
        $suppliers = Supplier::get();
        $request->merge(['start' =>  Carbon::now()->format("Y-m-d")]);

        return view('Admin.reports.pedidosreportsv2.main', [
            'pedidos' => [],
            'suppliers' => $suppliers,
            'utilizadores' => $utilizadores,
            'produtos' => $produtos
        ]);
    }

    public function applyFilter(Request $request)
    {
        set_time_limit(300);
        try {

            $produtos = Produto::orderBy('nome')->get();
            $utilizadores = User::orderBy('name')->get();
            $suppliers = Supplier::get();

            $pedidos = $this->filterRequest($request);

            if ($request->wantsJson()) {
                return response()->json($pedidos->toArray());
            } else {
                return view('Admin.reports.pedidosreportsv2.main', [
                    'pedidos' => $pedidos, 'suppliers' => $suppliers, 'utilizadores' => $utilizadores, 'produtos' => $produtos
                ]);
            }
        } catch (ModelException $ex) {
            return response($ex);
        }
    }

    public function filterRequest(Request $request)
    {
        // Start the query using the scope which includes base eager loading and potentially date filters
        // Pass the request data directly to the scope
        $pedidosQuery = PedidoGeral::ViewWithAllProd($request->all());

        // Apply direct filters on the pedido_gerals table
        if ($request->filled('pedidoid')) { // filled() checks for non-empty value
            $pedidosQuery->where('id', $request->get('pedidoid'));
        }

        if ($request->filled('client')) {
            $pedidosQuery->where('lead_name', 'like', '%' . $request->get('client') . "%");
        }

        // Apply status filter directly to the database query
        // This replaces the inefficient ->get()->filter() later
        $pedidosQuery->where('status', '!=', 'Cancelled');

        // --- Relationship Filters (using whereHas) ---

        // Consolidate Product/Supplier filtering if 'hotel' and 'suplier_id' filter on the same field (produto_id)
        // Assuming 'suplier_id' is actually meant to be a 'produto_id' based on the query logic. Rename if possible.
        $produtoIdFilter = null;
        if ($request->filled('hotel') && $request->get('hotel') !== '0') {
            $produtoIdFilter = $request->get('hotel');
        } elseif ($request->filled('suplier_id') && $request->get('suplier_id') !== '0') {
            // If 'suplier_id' filter should apply even if 'hotel' is present, adjust this logic
            $produtoIdFilter = $request->get('suplier_id');
        }

        if ($produtoIdFilter) {
            $pedidosQuery->whereHas('pedidoprodutos', function ($q) use ($produtoIdFilter) {
                $q->where('produto_id', $produtoIdFilter);
            });

            // Refine eager loading: Only load relations for the *filtered* products
            // This overwrites the broader eager loading from the scope for pedidoprodutos, which is often desired here.
            $pedidosQuery->with(['pedidoprodutos' => function ($q) use ($produtoIdFilter) {
                $q->where('produto_id', $produtoIdFilter);
                // Include nested relationships needed for these specific products
                $q->with('extras', 'valorquarto', 'pedidoquarto', 'valortransfer', 'pedidotransfer', 'valorgame', 'pedidogame', 'valorcar', 'pedidocar', 'valorticket', 'pedidoticket', 'produto');
            }]);
        }
        // If no specific product filter, the eager loading from `scopeViewWithAllProd` applies.


        if ($request->filled('operator') && $request->get('operator') !== '0') {
            $pedidosQuery->whereHas('user', function ($q) use ($request) {
                $q->where('id', $request->get('operator'));
            });
            // Note: 'user' is already eager loaded by scopeViewWithAllProd, no extra ->with() needed.
        }

        // --- Date Filtering ---
        // The date filtering logic is now handled *inside* the `scopeViewWithAllProd` -> `customDataFilters`
        // by applying WHERE HAS clauses *before* ->get().
        // Remove the inefficient PHP-based date filtering blocks that used ->filter().

        // --- Execute the Query ---
        // Now, ->get() fetches only the data filtered by the database.
        $pedidos = $pedidosQuery->orderBy('id', 'desc')->get(); // Added an example ordering, adjust as needed

        // The inefficient ->filter() calls previously here are REMOVED.

        return $pedidos;
    }

    public function reportPDF(Request $request)
    {
        try {

            $pedidos = $this->filterRequest($request);

            return view('Admin.reports.pedidosreports.print')->with('pedidos', $pedidos);
        } catch (Exception $ex) {
            dd($ex);
        }
    }

    public function reportExcel(Request $request)
    {
        try {
            $pedidos = $this->filterRequest($request);

            // return view('Admin.reports.pedidosreportsv2.Excel.report')->with('pedidos', $pedidos);

            return Excel::download(new PedidoGeralReport($pedidos, $request->ats), 'Export_' . Carbon::now()->format("Y-m-d H:i") . ".xls");
        } catch (Exception $ex) {
            dd($ex);
        }
    }
}
