<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Producto;

class ProductoApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_producto()
    {
        $producto = Producto::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/productos', $producto
        );

        $this->assertApiResponse($producto);
    }

    /**
     * @test
     */
    public function test_read_producto()
    {
        $producto = Producto::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/productos/'.$producto->id
        );

        $this->assertApiResponse($producto->toArray());
    }

    /**
     * @test
     */
    public function test_update_producto()
    {
        $producto = Producto::factory()->create();
        $editedProducto = Producto::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/productos/'.$producto->id,
            $editedProducto
        );

        $this->assertApiResponse($editedProducto);
    }

    /**
     * @test
     */
    public function test_delete_producto()
    {
        $producto = Producto::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/productos/'.$producto->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/productos/'.$producto->id
        );

        $this->response->assertStatus(404);
    }
}
