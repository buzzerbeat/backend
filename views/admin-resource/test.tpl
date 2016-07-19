{set title=$resource->desc}

<div class="body-content">
    <div class="row">

        <div class="col-md-2">
            id:
        </div>
        <div class="col-md-10">
            {$resource->id}
        </div>
        <div class="col-md-2">
            正文:
        </div>
        <div class="col-md-10">
            {$resource->desc}
        </div>
        <div class="col-md-2">
            类型:
        </div>
        <div class="col-md-10">
            {$resource->type}
        </div>
        <div class="col-md-2">
            视频:
        </div>
        <div class="col-md-10">
            {if (!empty($resource->relVideo))}
                {$resource->relVideo->url}
            {else}
                无
            {/if}
        </div>

        <div class="col-md-2">
            图片:
        </div>
        <div class="col-md-10">
            {if (!empty($resource->relmage))}
                {$resource->relImage->sid}
            {else}
                无
            {/if}
        </div>

        <div class="col-md-2">
            发布时间:
        </div>
        <div class="col-md-10">
            {$resource->pubTimeElapsed}
        </div>
        <div class="col-md-2">
            顶:
        </div>
        <div class="col-md-10">
            {$resource->dig}
        </div>
    </div>
</div>