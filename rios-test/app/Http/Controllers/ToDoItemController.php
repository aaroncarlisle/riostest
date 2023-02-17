<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreToDoItemRequest;
use App\Http\Requests\UpdateToDoItemRequest;
use App\Models\ToDoItem;

class ToDoItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$items = ToDoItem::all();
        /*
        $items = [];
        $item = ToDoItem::where('prev', null)->first();
        while ($item) {
            $items[] = $item;
            $item = ToDoItem::where('id', $item->next)->first();
        }
        return response()->json($items);
         */
        $items = ToDoItem::where('parent', null)->get();
        $items->load('children');
        return $items;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreToDoItemRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreToDoItemRequest $request)
    {
        $params = $request->post();
        $oldLast = ToDoItem::where('next', null)->first();
        if ($oldLast) {
            $params['prev'] = $oldLast->id;
        }
        $newLast = ToDoItem::create($params);
        if ($oldLast) {
            ToDoItem::where('id', $oldLast->id)->update([
                'next' => $newLast->id
            ]);
        }
        return response()->json([
            'message' => 'success',
            'item' => $newLast
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ToDoItem  $toDoItem
     * @return \Illuminate\Http\Response
     */
    public function show(ToDoItem $toDoItem)
    {
        return response()->json($toDoItem);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ToDoItem  $toDoItem
     * @return \Illuminate\Http\Response
     */
    public function edit(ToDoItem $toDoItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateToDoItemRequest  $request
     * @param  \App\Models\ToDoItem  $toDoItem
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateToDoItemRequest $request, $id)
    {
        ToDoItem::where('id', $id)->update($request->post());

        return response()->json([
            'message' => 'success'
        ]);
    }

    public function swap($id1, $id2)
    {
        $item1 = ToDoItem::where('id', $id1)->first();
        $item2 = ToDoItem::where('id', $id2)->first();

        if (!$item1 || !$item2) {
            return response()->json([
                'message' => 'failure'
            ], 500);
        }

        if ($item1->prev) {
            ToDoItem::where('id', $item1->prev)->update([
                'next' => $item2->id
            ]);
        }

        if ($item2->next) {
            ToDoItem::where('id', $item2->next)->update([
                'prev' => $item1->id
            ]);
        }

        ToDoItem::where('id', $id1)->update([
            'prev' => $item2->id,
            'next' => $item2->next
        ]);
        ToDoItem::where('id', $id2)->update([
            'prev' => $item1->prev,
            'next' => $item1->id
        ]);

        return response()->json([
            'message' => 'success'
        ]);
    }

    public function makechild($parentId, $newChildId) {
        $newChild = ToDoItem::where('id', $newChildId)->first();

        ToDoItem::where('id', $parentId)->update([
            'next' => $newChild->next
        ]);

        $lastChild = ToDoItem::where('parent', $parentId)
            ->where('next', null)->first();

        $lastChildId = null;
        if ($lastChild) {
            $lastChildId = $lastChild->id;
            ToDoItem::where('parent', $parentId)
                ->where('next', null)->update([
                    'next' => $newChildId
                ]);
        }

        ToDoItem::where('id', $newChildId)->update([
            'parent' => $parentId,
            'prev' => $lastChildId,
            'next' => null
        ]);

        return response()->json([
            'message' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ToDoItem  $toDoItem
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $toDoItem = ToDoItem::where('id', $id)->first();

        if ($toDoItem->prev) {
            ToDoItem::where('id', $toDoItem->prev)->update([
                'next' => $toDoItem->next
            ]);
        }
        if ($toDoItem->next) {
            ToDoItem::where('id', $toDoItem->next)->update([
                'prev' => $toDoItem->prev
            ]);
        }
        $toDoItem->delete();
        return response()->json([
            'message' => 'success'
        ]);
    }
}
