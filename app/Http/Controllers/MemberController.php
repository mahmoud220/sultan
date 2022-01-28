<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Setting;
use Illuminate\Http\Request;
use PDF;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('members.index');
    }

    public function data()
    {
        $member = Member::orderBy('code_member')->get();

        return datatables()
            ->of($member)
            ->addIndexColumn()
            ->addColumn('select_all', function ($product) {
                return '
                    <input type="checkbox" name="id_member[]" value="'. $product->id_member .'">
                ';
            })
            ->addColumn('code_member', function ($member) {
                return '<span class="label label-success">'. $member->code_member .'<span>';
            })
            ->addColumn('Added', function ($member) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('members.update', $member->id_member) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('members.destroy', $member->id_member) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['Added', 'select_all', 'code_member'])
            ->make(true);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $member = Member::latest()->first() ?? new Member();
        $code_member = (int) $member->code_member +1;

        $member = new Member();
        $member->code_member = add_zero_front($code_member, 5);
        $member->name = $request->name;
        $member->phone = $request->phone;
        $member->address = $request->address;
        $member->save();

        return response()->json('Data Added Succfully', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $member = Member::find($id);

        return response()->json($member);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function edit(Member $member)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $member = Member::find($id)->update($request->all());

        return response()->json('Data Updated Succfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $member = Member::find($id);
        $member->delete();

        return response(null, 204);
    }

    public function printMember(Request $request)
    {
        $datamember = collect(array());
        foreach ($request->id_member as $id) {
            $member = Member::find($id);
            $datamember[] = $member;
        }

        $datamember = $datamember->chunk(2);
//        $setting    = Setting::first();

        $no  = 1;
        $pdf = PDF::loadView('members.print', compact('datamember', 'no'));
        $pdf->setPaper(array(0, 0, 566.93, 850.39), 'potrait');
        return $pdf->stream('member.pdf');
    }
}
