<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Country;
use App\Models\District;
use App\Models\Street;
use App\Models\Ward;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_correct_data(): void
    {
        // Tạo dữ liệu mẫu
        $country = Country::factory()->create();
        $city = City::factory()->create(['country_id' => $country->id]);
        $district = District::factory()->create(['city_id' => $city->id]);
        $ward = Ward::factory()->create(['district_id' => $district->id]);
        $street = Street::factory()->create(['ward_id' => $ward->id]); // Sửa ở đây

        // Gửi request đến index
        $response = $this->get(route('index', [
            'country' => $country->id,
            'city' => $city->id,
            'district' => $district->id,
            'ward' => $ward->id,
            'street' => $street->id,
        ]));

        // Kiểm tra xem view được trả về có dữ liệu đúng không
        $response->assertStatus(200);
        $response->assertViewHas('countries');
        $response->assertViewHas('cities');
        $response->assertViewHas('districts');
        $response->assertViewHas('wards');
        $response->assertViewHas('streets');
        $response->assertViewHas('addressString', "{$street->name}, {$ward->name}, {$district->name}, {$city->name}, {$country->name}.");
    }

    public function test_store_address_saves_data_correctly(): void
    {
        // Dữ liệu giả lập để gửi request
        $requestData = [
            'country_name' => 'Viet Nam',
            'city_name' => 'Ho Chi Minh City',
            'district_name' => 'Quan 11',
            'ward_name' => 'Phuong 15',
            'street_name' => 'Le Dai Hanh',
        ];

        // Gửi request POST để lưu dữ liệu
        $response = $this->post(route('storeAddress'), $requestData);

        // Kiểm tra dữ liệu có được lưu đúng không
        $this->assertDatabaseHas('countries', ['name' => 'Viet Nam']);
        $this->assertDatabaseHas('cities', ['name' => 'Ho Chi Minh City']);
        $this->assertDatabaseHas('districts', ['name' => 'Quan 11']);
        $this->assertDatabaseHas('wards', ['name' => 'Phuong 15']);
        $this->assertDatabaseHas('streets', ['name' => 'Le Dai Hanh']);

        // Kiểm tra phản hồi redirect và thông báo session
        $response->assertRedirect('/index');
        $response->assertSessionHas('success', 'Address has been added successfully');
    }

    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
