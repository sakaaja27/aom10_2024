<?php
namespace App\Services;
use App\Models\Sponsorships;
use Illuminate\Support\Facades\Storage;
class SponsorshipService
{
    public function getData()
    {
        $sponsorships = Sponsorships::with('sponsorshipcategories')->orderby('id', 'desc')->get();
        return $sponsorships;
    }
    public function getWhere($id)
    {
        $sponsorships = Sponsorships::where('id', $id)->get();
        return $sponsorships;
    }
    public function store($data)
    {
        $data->validate([
            'nama_sponsor' => 'required',
            'id_sponsorship_categori' => 'required',
        ]);
        // dd($data);
        $logo = $data->logo;
        $filename = uniqid() . '.' . $logo->extension();
        $dtUpload = new Sponsorships();
        $dtUpload->name = $data->nama_sponsor;
        $dtUpload->id_sponsorship_categori = $data->id_sponsorship_categori;
        $dtUpload->logo = $data->file('logo')->store('sponsor');
        $save = $dtUpload->save();

        if ($save) {
            return $store = '1';
        } else {
            return $store = '0';
        }
    }
    public function update($data, $id)
    {
        $sponsorships = Sponsorships::find($id);
        $dataupdate = [
            'nama_sponsor' => $data['nama_sponsor'],
            'id_sponsorship_categori' => $data['id_sponsorship_categori'],
        ];
        
        $existingLogo = $sponsorships->logo; 
        if (!empty($data->logo)) {
            Storage::disk('local')->delete($existingLogo); 
            $picture = uniqid() . '.' . $data->logo->extension();
            $dataupdate['logo'] = $data->file('logo')->store('sponsor');
        }

        $save = $sponsorships->update($dataupdate);
        if ($save) {
            return $store = '1';
        } else {
            return $store = '0';
        }
    }

    public function destroy($data)
    {
        $sponsorships = Sponsorships::findorfail($data);
        $file = $sponsorships->logo; 
        if (!empty($file)) {
            Storage::disk('local')->delete($file); 
        }
        $del = $sponsorships->delete();
        if ($del) {
            return $destroy = '1';
        } else {
            return $destroy = '0';
        }
    }
}
?>
