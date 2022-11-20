<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateProductoAPIRequest;
use App\Http\Requests\API\UpdateProductoAPIRequest;
use App\Models\Producto;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class ProductoController
 * @package App\Http\Controllers\API
 */

class ProductoAPIController extends AppBaseController
{
    /**
     * Display a listing of the Producto.
     * GET|HEAD /productos
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $query = Producto::query();

        if ($request->get('skip')) {
            $query->skip($request->get('skip'));
        }
        if ($request->get('limit')) {
            $query->limit($request->get('limit'));
        }

        $productos = $query->get();

        return $this->sendResponse($productos->toArray(), 'Productos retrieved successfully');
    }

    /**
     * Store a newly created Producto in storage.
     * POST /productos
     *
     * @param CreateProductoAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateProductoAPIRequest $request)
    {
        $input = $request->all();

        /** @var Producto $producto */
        $producto = Producto::create($input);

        return $this->sendResponse($producto->toArray(), 'Producto guardado exitosamente');
    }

    /**
     * Display the specified Producto.
     * GET|HEAD /productos/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Producto $producto */
        $producto = Producto::find($id);

        if (empty($producto)) {
            return $this->sendError('Producto no encontrado');
        }

        return $this->sendResponse($producto->toArray(), 'Producto retrieved successfully');
    }

    /**
     * Update the specified Producto in storage.
     * PUT/PATCH /productos/{id}
     *
     * @param int $id
     * @param UpdateProductoAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProductoAPIRequest $request)
    {
        /** @var Producto $producto */
        $producto = Producto::find($id);

        if (empty($producto)) {
            return $this->sendError('Producto no encontrado');
        }

        $producto->fill($request->all());
        $producto->save();

        return $this->sendResponse($producto->toArray(), 'Producto actualizado con éxito');
    }

    /**
     * Remove the specified Producto from storage.
     * DELETE /productos/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Producto $producto */
        $producto = Producto::find($id);

        if (empty($producto)) {
            return $this->sendError('Producto no encontrado');
        }

        $producto->delete();

        return $this->sendSuccess('Producto deleted successfully');
    }
}
