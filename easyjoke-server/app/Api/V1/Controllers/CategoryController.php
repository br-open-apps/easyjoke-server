<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\Category\CategoryRequest;
use App\Api\V1\Transformers\CategoryCollectionTransformer;
use App\Api\V1\Transformers\CategoryItemTransformer;
use App\Api\V1\Transformers\CategoryJokeItemTransformer;
use App\Models\Category;
use Illuminate\Http\Request;

/**
 * @Resource("Categories", uri="/categories")
 */
class CategoryController extends ApiController
{
    /**
     * List categories
     *
     * List all categories
     *
     * @Get("/")
     * @Versions({"v1"})
     * @Transaction({
     *      @Response(200, body={
     *          "data": {
     *              {
     *                  "id": 1,
     *                  "name": "Category One"
     *              },
     *              {
     *                  "id": 2,
     *                  "name": "Category Two"
     *              }
     *          },
     *          "meta": {
     *              "pagination": {
     *                  "total": 1,
     *                  "count": 1,
     *                  "per_page": 15,
     *                  "current_page": 1,
     *                  "total_pages": 1,
     *                  "links": {}
     *              }
 *              }
     *      }),
     * })
     */
    public function index()
    {
        return $this->response->paginator(Category::paginate(), new CategoryCollectionTransformer());
    }

    /**
     * Create category
     *
     * Create a new category
     *
     * @Post("/")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(body={
     *          "name": "Category Name"
     *      }, attributes={
     *          @Attribute("name", type="string", description="The category name", sample="Category Name", required=true),
     *      }),
     *      @Response(200, body={
     *          "data": {
     *              "id": 1,
     *              "name": "Category Name"
     *          }
     *      }),
     *      @Response(422, body={
     *          "message": "422 Unprocessable Entity",
     *          "errors": {
     *              "name": {"The name field is required."}
     *          },
     *          "status_code": 422
     *      })
     * })
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create(array_merge(
            $request->only(config('easyjoke.category_fields'))
        ));

        return $this->response->item($category, new CategoryItemTransformer());
    }

    /**
     * Show category
     *
     * Show a specified category
     *
     * @Get("/:id")
     * @Versions({"v1"})
     * @Transaction({
     *      @Response(200, body={
     *          "data": {
     *              "id": 1,
     *              "name": "Category One"
     *          }
     *      }),
     *      @Response(404, body={
     *          "message": "Category not found",
     *          "status_code": 404
     *     })
     * })
     * @Parameters({
     *      @Parameter("id", type="integer", description="The category id.", required=true)
     * })
     */
    public function show(Request $request, $id)
    {
        if ($category = Category::find($id)){
            return $this->response->item($category, new CategoryItemTransformer());
        } else {
            return $this->response->errorBadRequest('Category not found');
        }
    }

    /**
     * Update category
     *
     * Update a specified category
     *
     * @Put("/:id")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="integer", description="The category id.", required=true)
     * })
     * @Transaction({
     *      @Request(body={
     *          "name": "Update Category Name"
     *      }, attributes={
     *          @Attribute("name", type="string", description="The category name", sample="Update Category Name", required=true),
     *      }),
     *      @Response(200, body={
     *          "data": {
     *              "id": 1,
     *              "name": "Update Category Name"
     *          }
     *      }),
     *      @Response(422, body={
     *          "message": "422 Unprocessable Entity",
     *          "errors": {
     *              "name": {"The name field is required."}
     *          },
     *          "status_code": 422
     *      }),
     *     @Response(404, body={
     *          "message": "Category not found",
     *          "status_code": 404
     *      })
     * })
     */
    public function update(CategoryRequest $request, $id)
    {
        if (!$category = Category::find($id)){
            return $this->response->errorBadRequest('Category not found');
        }

        /** @var Category $category */
        $category->update($request->only(config('easyjoke.category_fields')));
        return $this->response->item($category, new CategoryItemTransformer());
    }

    /**
     * Delete category
     *
     * Remove a specified category.
     *
     * @Delete("/:id")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="integer", description="The category id.", required=true)
     * })
     * @Transaction({
     *      @Response(200, body=""),
     *      @Response(400, body={
     *          "message": "Bad Request",
     *          "status_code": 400
     *      })
     * })
     */
    public function destroy($id)
    {
        return Category::destroy($id) ? $this->response->noContent() : $this->response->errorBadRequest();
    }

    /**
     * List all jokes
     *
     * List all the company's jokes
     *
     * @Get("/")
     * @Versions({"v1"})
     * @Transaction({
     *      @Response(200, body={
     *          "data": {
     *              {
     *                  "id": 1,
     *                  "title": "Joke One",
     *                  "content": "Content of the joke one",
     *              },
     *              {
     *                  "id": 2,
     *                  "title": "Joke Two",
     *                  "content": "Content of the joke two",
     *              }
     *          },
     *          "meta": {
     *              "pagination": {
     *                  "total": 2,
     *                  "count": 2,
     *                  "per_page": 15,
     *                  "current_page": 1,
     *                  "total_pages": 1,
     *                  "links": {}
     *              }
     *          }
     *      }),
     * })
     */
    public function jokes(Request $request, $id) {

        if (!$category = Category::find($id)){
            return $this->response->errorBadRequest('Category not found');
        }

        /** @var Category $category */
        return $this->response->paginator($category->jokes()->paginate(), new CategoryJokeItemTransformer());
    }
}