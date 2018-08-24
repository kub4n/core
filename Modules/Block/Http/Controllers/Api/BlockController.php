<?php

namespace Modules\Block\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Block\Entities\Block;
use Modules\Block\Http\Requests\CreateBlockRequest;
use Modules\Block\Http\Requests\UpdateBlockRequest;
use Modules\Block\Repositories\BlockRepository;
use Modules\Block\Transformers\FullBlockTransformer;
use Modules\Block\Transformers\BlockTransformer;

class BlockController extends Controller
{
    /**
     * @var BlockRepository
     */
    private $block;

    public function __construct(BlockRepository $block)
    {
        $this->block = $block;
    }

    public function index()
    {
        return BlockTransformer::collection($this->block->all());
    }

    public function indexServerSide(Request $request)
    {

        return BlockTransformer::collection($this->block->serverPaginationFilteringFor($request));
    }

    public function store(CreateBlockRequest $request)
    {
        $this->block->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('block::messages.block created'),
        ]);
    }

    public function find(Block $block)
    {

        return new FullBlockTransformer($block);
    }

    public function update(Block $block, UpdateBlockRequest $request)
    {
        $this->block->update($block, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('block::messages.block updated'),
        ]);
    }

    public function destroy(Block $block)
    {
        $this->block->destroy($block);

        return response()->json([
            'errors' => false,
            'message' => trans('block::messages.block deleted'),
        ]);
    }
}
