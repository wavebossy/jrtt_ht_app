<script>
    function progress(e){
        $("#progress_div").show();
        switch (e){
            case 1 : $("#progress").width("10%");break;
            case 2 : $("#progress").width("20%");break;
            case 3 : $("#progress").width("30%");break;
            case 4 : $("#progress").width("40%");break;
            case 5 : $("#progress").width("50%");break;
            case 6 : $("#progress").width("60%");break;
            case 7 : $("#progress").width("70%");break;
            case 8 : $("#progress").width("80%");break;
            case 9 : $("#progress").width("90%");break;
            case 10 : $("#progress").width("100%");setTimeout(function () {
                $("#progress_div").hide();
                $("#progress").width("0%");
            },500);break;
        }
    }
</script>