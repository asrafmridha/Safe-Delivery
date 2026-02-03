<template>
    <div class="item">
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item" style="white-space: normal;">
            Parcel <b>{{ unread.data.parcel_info.parcel_invoice }}</b> is {{ unread.data.parcel_info.status_name }}
        </a>
    </div>
</template>

<script>
    export default {
        props: ['unread'],
        data() {
            return {

            }
        }
    }
</script>
