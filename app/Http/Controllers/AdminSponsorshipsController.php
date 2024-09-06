<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SponsorshipService;
use App\Models\sponsorship_categori;

class AdminSponsorshipsController extends Controller
{
    public function index(SponsorshipService $sponsorshipService)
    {
       $sponsorshipscategoriesdata = sponsorship_categori::get();
        $sponsorships = $sponsorshipService->getData();
        return view('pages.admin.sponsorship.sponsorships',compact('sponsorships','sponsorshipscategoriesdata'));
    }
    public function store(Request $request,SponsorshipService $sponsorshipService)
    {
        $store=$sponsorshipService->store($request);   
        if ($store=='1') {
            toast('Data berhasil ditambahkan!', 'success');
            return redirect()->back();
        }else{
            toast('Gagal!', 'error');
            return redirect()->back();
        }
    }

    public function edit(SponsorshipService $sponsorshipService,$id){
        $sponsorshipscategoriesdata = sponsorship_categori::get();
        $sponsorships= $sponsorshipService->getWhere($id)->first();
       return view('pages.admin.sponsorship.editsponsorship',compact('sponsorships','sponsorshipscategoriesdata'));
    }

    public function update(SponsorshipService $sponsorshipService,Request $request,$id){
        $this->validate($request, [
            'nama_sponsor' => 'required',
            'id_sponsorship_categori' => 'required',
        ]);
        $update = $sponsorshipService->update($request,$id);
        if ($update == 1) {
            toast('Data berhasil diubah!', 'success');
            return redirect()->route('admin.sponsorship');
        }else{
            toast('Gagal!', 'error');
            return redirect()->back();
        }
    }

    public function destroy($id, SponsorshipService $sponsorshipService)
    {
        $sponsorships=$sponsorshipService->destroy($id);
        if ($sponsorships=='1') {
            toast('Data berhasil dihapus!', 'success');
            return redirect()->back();
        }else{
            toast('Gagal', 'error');
            return redirect()->back();
        }
    }
}
