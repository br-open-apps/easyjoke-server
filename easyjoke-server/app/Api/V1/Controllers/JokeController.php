<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\Joke\JokeRequest;
use App\Api\V1\Transformers\JokeCollectionTransformer;
use App\Api\V1\Transformers\JokeItemTransformer;
use App\Models\Joke;
use Illuminate\Http\Request;

/**
 * @Resource("Jokes", uri="/jokes")
 */
class JokeController extends ApiController
{
    /**
     * List jokes
     *
     * List all jokes
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
    public function index()
    {
        return $this->response->paginator(Joke::with('categories')->where(['approved' => true])->paginate(), new JokeCollectionTransformer());
    }

    /**
     * Create joke
     *
     * Create a new joke
     *
     * @Post("/")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(body={
     *          "title": "Joke title",
     *          "content": "Joke content"
     *      }, attributes={
     *          @Attribute("title", type="string", description="The joke title", sample="Happy Joke", required=true),
     *          @Attribute("content", type="string", description="The joke content", sample="Seriously, this is my happy face", required=true),
     *      }),
     *      @Response(200, body={
     *          "data": {
     *              "id": 1,
     *              "title": "Happy Joke",
     *              "content": "Seriously, this is my happy face",
     *          }
     *      }),
     *      @Response(422, body={
     *          "message": "422 Unprocessable Entity",
     *          "errors": {
     *              "title": {"The title field is required."},
     *              "content": {"The content field is required."}
     *          },
     *          "status_code": 422
     *      })
     * })
     */
    public function store(JokeRequest $request)
    {
        $joke = Joke::create(array_merge(
            $request->only(config('easyjoke.joke_fields')),
            ['approved' => true]
        ));
        $joke->categories()->sync($request->all()['categories']);

        return $this->response->item($joke, new JokeItemTransformer());
    }

    /**
     * Show joke
     *
     * Show a specified joke
     *
     * @Get("/:id")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="integer", description="The joke id.", required=true)
     * })
     * @Transaction({
     *      @Response(200, body={
     *          "data": {
     *              "id": 1,
     *              "title": "Happy Joke",
     *              "content": "Seriously, this is my happy face",
     *          }
     *      }),
     *      @Response(400, body={
     *          "message": "Joke not found",
     *          "status_code": 400
     *     })
     * })
     * @Parameters({
     *      @Parameter("id", type="integer", description="The company id.", required=true)
     * })
     */
    public function show(Request $request, $id)
    {
        /** @var Joke $joke */
        if ($joke = Joke::find($id)){
            return $this->response->item($joke, new JokeItemTransformer());
        } else {
            return $this->response->errorBadRequest('Joke not found');
        }
    }

    /**
     * Update joke
     *
     * Update a specified joke
     *
     * @Put("/:id")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="integer", description="The joke id.", required=true)
     * })
     * @Transaction({
     *      @Request(body={
     *          "title": "Joke title",
     *          "content": "Joke content"
     *      }, attributes={
     *          @Attribute("title", type="string", description="The joke title", sample="Update Happy Joke", required=true),
     *          @Attribute("content", type="string", description="The joke content", sample="Seriously, this is my happy face", required=true),
     *      }),
     *      @Response(200, body={
     *          "data": {
     *              "id": 1,
     *              "title": "Update Happy Joke",
     *              "content": "Seriously, this is my happy face",
     *          }
     *      }),
     *      @Response(422, body={
     *          "message": "422 Unprocessable Entity",
     *          "errors": {
     *              "title": {"The title field is required."},
     *              "content": {"The content field is required."}
     *          },
     *          "status_code": 422
     *      }),
     *     @Response(400, body={
     *          "message": "Joke not found",
     *          "status_code": 400
     *      })
     * })
     */
    public function update(JokeRequest $request, $id)
    {
        if (!$joke = Joke::find($id)){
            return $this->response->errorBadRequest('Category not found');
        }

        /** @var Joke $joke */
        $joke->update($request->only(config('easyjoke.joke_fields')));
        $joke->categories()->sync($request->all()['categories']);
        return $this->response->item($joke, new JokeItemTransformer());
    }

    /**
     * Delete joke
     *
     * Remove a specified joke.
     *
     * @Delete("/:id")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="integer", description="The joke id.", required=true)
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
        return Joke::destroy($id) ? $this->response->noContent() : $this->response->errorBadRequest();
    }

}