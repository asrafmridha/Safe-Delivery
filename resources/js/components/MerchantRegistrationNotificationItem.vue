<template>
    <div class="item">
        <div class="dropdown-divider"></div>
        <a :href="'/admin/merchant/'+ unread.data.merchant.id +'/edit'" target="_blank" class="dropdown-item" style="white-space: normal;">
            Newly registered Merchant <b>{{ unread.data.merchant.company_name }}</b> And Merchant ID <b>{{ unread.data.merchant.m_id }}</b>
        </a>
    </div>
</template>

<script>
    export default {
        props: ['unread'],
        data() {
            return {
                //url_host: window.location.pathname,
            }
        }
    }
</script>
