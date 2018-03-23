<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta content="联系邮箱" name="482353779@qq.com" />
    <meta content="尧涛" name="author" />
    @if(!empty($noticeDetail))
        <title>{{$noticeDetail->title}}</title>
    @else
        <title>发布的公告不存在</title>
    @endif
</head>
<body>
    <div>
        <div id="page-wrapper">
            <div id="page-inner">
                @if(!empty($noticeDetail))
                    <h3>{{$noticeDetail->title}}</h3>
                    <label>{{$noticeDetail->times}}</label>
                    {!! $noticeDetail->bodys !!}
                    @else
                    发布的公告不存在
                @endif
            </div>
        </div>
    </div>
</body>