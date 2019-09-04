<?php

namespace App\Admin\Controllers;

use App\Models\Members;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Members as Member;
use App\Models\MemberFollow;
use App\Models\MemberEducation;
use App\Models\Accounts;
use App\Models\AccountLogs;
use App\Models\Levels as Level;
use App\Models\Region;
use App\Models\Favorites;

class MembersController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Models\Members';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Member);
        $grid->disableCreateButton();
        $grid->disableActions();
        $grid->filter(function($filter){
            // 在这里添加字段过滤器
            $filter->like('nickname', '昵称')->placeholder('请输入昵称');
            $filter->equal('phone', '手机号码')->placeholder('请输入手机号码');
        });

        $grid->id('ID')->sortable();
        $grid->avatar('头像')->display(function ($avatar) {
            $el = <<< EOT
            <a href="{$avatar}" class="grid-popup-link"> <img src="{$avatar}" style="max-width:50px;max-height:50px" class="img img-thumbnail"> </a>
EOT;
            return $el;
        });
        $grid->nickname('昵称');
        $grid->sign('个性签名')->editable();
        $grid->sex('性别')->using(['2' => '男', '1' => '女']);
        $grid->age('年龄')->sortable();
        $grid->born('生日');
        $grid->region_id('所在地')->display(function($region_id){
            $region = Region::where('id', $region_id)->first();
            if ($region) return $region->MergerName;
        });
        $grid->phone('手机号码');
        $grid->email('邮箱');
        $grid->weixin('微信');
        $grid->job('职业')->using(['1' => '学生', '1' => '老师']);
        $grid->education_id('学历')->display(function($education_id){
            $result = MemberEducation::where('id', $education_id)->first();
            return $result->name;
        });
        $grid->school('学校');
        $grid->department('院系');
        $grid->professional('专业');
        $grid->start_school_at('入学年份');
        $grid->next_plan('近期动向');
        $grid->hobby('兴趣爱好')->editable();
        $grid->follow('关注')->display(function(){
            $count = MemberFollow::where('uid', $this->id)->count();
            return $count;
        });
        $grid->fans('粉丝')->display(function(){
            $count = MemberFollow::where('follow_uid', $this->id)->count();
            return $count;
        });
        $grid->balance('龙币');
        $grid->level('等级')->display(function(){
            $money = AccountLogs::getMaxBetweenTimeByUid($this->id,  time() - 60*60*24*365);
            $fans = MemberFollow::countFansBYUid($this->id);
            $has_level = Level::getLevelByFansAndMony($fans, $money);
            if ($has_level)
                return $has_level->name;
            else
                return "暂无等级";
        });
        $grid->status('状态')->using(['1'=>'正常', '0'=>'禁用'])
            ->label([
                0 => 'warning',
                1 => 'success'
            ])->filter(
                [
                    0 => '禁用',
                    1 => '正常',
                ]
            );
        $grid->created_at('创建时间');
        return $grid;

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Members::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('avatar', __('Avatar'));
        $show->field('email', __('Email'));
        $show->field('email_verified_at', __('Email verified at'));
        $show->field('password', __('Password'));
        $show->field('remember_token', __('Remember token'));
        $show->field('openid', __('Openid'));
        $show->field('nickname', __('Nickname'));
        $show->field('region_id', __('Region id'));
        $show->field('sign', __('Sign'));
        $show->field('sex', __('Sex'));
        $show->field('age', __('Age'));
        $show->field('born', __('Born'));
        $show->field('job', __('Job'));
        $show->field('weixin', __('Weixin'));
        $show->field('phone', __('Phone'));
        $show->field('phone_verified_at', __('Phone verified at'));
        $show->field('phone_verified_code', __('Phone verified code'));
        $show->field('school', __('School'));
        $show->field('department', __('Department'));
        $show->field('professional', __('Professional'));
        $show->field('education_id', __('Education id'));
        $show->field('start_school_at', __('Start school at'));
        $show->field('hobby', __('Hobby'));
        $show->field('next_plan', __('Next plan'));
        $show->field('updated_at', __('Updated at'));
        $show->field('created_at', __('Created at'));
        $show->field('status', __('Status'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Members);

        $form->text('name', __('Name'));
        $form->image('avatar', __('Avatar'));
        $form->email('email', __('Email'));
        $form->text('email_verified_at', __('Email verified at'));
        $form->password('password', __('Password'));
        $form->text('remember_token', __('Remember token'));
        $form->text('openid', __('Openid'));
        $form->text('nickname', __('Nickname'));
        $form->number('region_id', __('Region id'));
        $form->text('sign', __('Sign'));
        $form->number('sex', __('Sex'));
        $form->number('age', __('Age'));
        $form->datetime('born', __('Born'))->default(date('Y-m-d H:i:s'));
        $form->number('job', __('Job'))->default(1);
        $form->text('weixin', __('Weixin'));
        $form->number('phone', __('Phone'));
        $form->number('phone_verified_at', __('Phone verified at'));
        $form->text('phone_verified_code', __('Phone verified code'));
        $form->text('school', __('School'));
        $form->text('department', __('Department'));
        $form->text('professional', __('Professional'));
        $form->number('education_id', __('Education id'));
        $form->datetime('start_school_at', __('Start school at'))->default(date('Y-m-d H:i:s'));
        $form->text('hobby', __('Hobby'));
        $form->text('next_plan', __('Next plan'));
        $form->number('status', __('Status'));

        return $form;
    }
}
