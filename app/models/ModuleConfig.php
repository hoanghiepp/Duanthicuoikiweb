<?php
declare(strict_types=1);

class ModuleConfig
{
    public static function get(string $name): array
    {
        $configs = [
            'departments' => [
                'title'=>'phòng ban','table'=>'departments','page'=>'departments','role'=>['admin','hr'],
                'columns'=>['department_code'=>'Mã phòng','name'=>'Tên phòng ban','manager_name'=>'Trưởng phòng','phone'=>'SĐT'],
                'fields'=>[
                    'department_code'=>['label'=>'Mã phòng ban','type'=>'text','required'=>true,'unique'=>true],
                    'name'=>['label'=>'Tên phòng ban','type'=>'text','required'=>true],
                    'manager_name'=>['label'=>'Trưởng phòng','type'=>'text'],
                    'phone'=>['label'=>'Số điện thoại','type'=>'text'],
                    'description'=>['label'=>'Mô tả','type'=>'textarea'],
                ],
                'search'=>['department_code','name','manager_name','phone']
            ],
            'positions' => [
                'title'=>'chức vụ','table'=>'positions','page'=>'positions','role'=>['admin','hr'],
                'columns'=>['position_code'=>'Mã chức vụ','name'=>'Tên chức vụ','base_salary'=>'Lương cơ bản','allowance'=>'Phụ cấp'],
                'fields'=>[
                    'position_code'=>['label'=>'Mã chức vụ','type'=>'text','required'=>true,'unique'=>true],
                    'name'=>['label'=>'Tên chức vụ','type'=>'text','required'=>true],
                    'base_salary'=>['label'=>'Lương cơ bản','type'=>'number','required'=>true],
                    'allowance'=>['label'=>'Phụ cấp','type'=>'number'],
                    'description'=>['label'=>'Mô tả','type'=>'textarea'],
                ],
                'search'=>['position_code','name']
            ],
            'employees' => [
                'title'=>'nhân viên','table'=>'employees','page'=>'employees','role'=>['admin','hr'],
                'select'=>'e.*, d.name department_name, p.name position_name',
                'from'=>'employees e LEFT JOIN departments d ON d.id=e.department_id LEFT JOIN positions p ON p.id=e.position_id',
                'alias'=>'e',
                'columns'=>['employee_code'=>'Mã NV','full_name'=>'Họ tên','email'=>'Email','department_name'=>'Phòng ban','position_name'=>'Chức vụ','status'=>'Trạng thái'],
                'fields'=>[
                    'employee_code'=>['label'=>'Mã nhân viên','type'=>'text','required'=>true,'unique'=>true],
                    'full_name'=>['label'=>'Họ tên','type'=>'text','required'=>true],
                    'email'=>['label'=>'Email','type'=>'email','unique'=>true],
                    'phone'=>['label'=>'Số điện thoại','type'=>'text'],
                    'gender'=>['label'=>'Giới tính','type'=>'select','options'=>['male'=>'Nam','female'=>'Nữ','other'=>'Khác']],
                    'birth_date'=>['label'=>'Ngày sinh','type'=>'date'],
                    'department_id'=>['label'=>'Phòng ban','type'=>'select_table','source'=>'departments'],
                    'position_id'=>['label'=>'Chức vụ','type'=>'select_table','source'=>'positions'],
                    'hire_date'=>['label'=>'Ngày vào làm','type'=>'date'],
                    'status'=>['label'=>'Trạng thái','type'=>'select','options'=>['working'=>'Đang làm','probation'=>'Thử việc','resigned'=>'Đã nghỉ']],
                    'address'=>['label'=>'Địa chỉ','type'=>'textarea'],
                ],
                'filters'=>[
                    'department_id'=>['label'=>'Phòng ban','source'=>'departments'],
                    'position_id'=>['label'=>'Chức vụ','source'=>'positions'],
                    'status'=>['label'=>'Trạng thái','options'=>['working'=>'Đang làm','probation'=>'Thử việc','resigned'=>'Đã nghỉ']],
                ],
                'search'=>['e.employee_code','e.full_name','e.email','e.phone']
            ],
            'attendance' => [
                'title'=>'chấm công','table'=>'attendance','page'=>'attendance','role'=>['admin','hr'],
                'select'=>'a.*, e.employee_code, e.full_name',
                'from'=>'attendance a JOIN employees e ON e.id=a.employee_id',
                'alias'=>'a',
                'columns'=>['work_date'=>'Ngày','employee_code'=>'Mã NV','full_name'=>'Họ tên','status'=>'Trạng thái','check_in'=>'Giờ vào','check_out'=>'Giờ ra'],
                'fields'=>[
                    'employee_id'=>['label'=>'Nhân viên','type'=>'select_table','required'=>true,'source'=>'employees'],
                    'work_date'=>['label'=>'Ngày','type'=>'date','required'=>true],
                    'status'=>['label'=>'Trạng thái','type'=>'select','options'=>['present'=>'Có mặt','late'=>'Đi muộn','absent'=>'Vắng','leave'=>'Nghỉ phép']],
                    'check_in'=>['label'=>'Giờ vào','type'=>'time'],
                    'check_out'=>['label'=>'Giờ ra','type'=>'time'],
                    'note'=>['label'=>'Ghi chú','type'=>'textarea'],
                ],
                'filters'=>[
                    'work_date'=>['label'=>'Ngày','type'=>'date'],
                    'status'=>['label'=>'Trạng thái','options'=>['present'=>'Có mặt','late'=>'Đi muộn','absent'=>'Vắng','leave'=>'Nghỉ phép']],
                ],
                'search'=>['e.employee_code','e.full_name']
            ],
            'payrolls' => [
                'title'=>'bảng lương','table'=>'payrolls','page'=>'payrolls','role'=>['admin','hr'],
                'select'=>'pr.*, e.employee_code, e.full_name',
                'from'=>'payrolls pr JOIN employees e ON e.id=pr.employee_id',
                'alias'=>'pr',
                'columns'=>['salary_month'=>'Tháng','employee_code'=>'Mã NV','full_name'=>'Họ tên','base_salary'=>'Lương CB','allowance'=>'Phụ cấp','bonus'=>'Thưởng','deduction'=>'Khấu trừ','net_salary'=>'Thực nhận'],
                'fields'=>[
                    'employee_id'=>['label'=>'Nhân viên','type'=>'select_table','required'=>true,'source'=>'employees_salary'],
                    'salary_month'=>['label'=>'Tháng lương','type'=>'month','required'=>true],
                    'base_salary'=>['label'=>'Lương cơ bản','type'=>'number','required'=>true],
                    'allowance'=>['label'=>'Phụ cấp','type'=>'number'],
                    'bonus'=>['label'=>'Thưởng','type'=>'number'],
                    'deduction'=>['label'=>'Khấu trừ','type'=>'number'],
                    'note'=>['label'=>'Ghi chú','type'=>'textarea'],
                ],
                'filters'=>['salary_month'=>['label'=>'Tháng','type'=>'month']],
                'search'=>['e.employee_code','e.full_name']
            ],
            'users' => [
                'title'=>'tài khoản','table'=>'users','page'=>'users','role'=>['admin'],
                'select'=>'u.*, e.employee_code',
                'from'=>'users u LEFT JOIN employees e ON e.id=u.employee_id',
                'alias'=>'u',
                'columns'=>['username'=>'Username','full_name'=>'Họ tên','email'=>'Email','role'=>'Vai trò','employee_code'=>'Mã NV','status'=>'Trạng thái'],
                'fields'=>[
                    'username'=>['label'=>'Tên đăng nhập','type'=>'text','required'=>true,'unique'=>true],
                    'password'=>['label'=>'Mật khẩu','type'=>'password','required_create'=>true],
                    'full_name'=>['label'=>'Họ tên','type'=>'text','required'=>true],
                    'email'=>['label'=>'Email','type'=>'email','unique'=>true],
                    'role'=>['label'=>'Vai trò','type'=>'select','options'=>['admin'=>'Admin','hr'=>'Nhân sự','employee'=>'Nhân viên']],
                    'employee_id'=>['label'=>'Liên kết nhân viên','type'=>'select_table','source'=>'employees'],
                    'status'=>['label'=>'Trạng thái','type'=>'select','options'=>['active'=>'Hoạt động','locked'=>'Đã khóa']],
                ],
                'filters'=>[
                    'role'=>['label'=>'Vai trò','options'=>['admin'=>'Admin','hr'=>'Nhân sự','employee'=>'Nhân viên']],
                    'status'=>['label'=>'Trạng thái','options'=>['active'=>'Hoạt động','locked'=>'Đã khóa']],
                ],
                'search'=>['u.username','u.full_name','u.email']
            ],
        ];
        if (!isset($configs[$name])) throw new RuntimeException('Module không tồn tại.');
        return $configs[$name];
    }
}
