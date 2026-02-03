<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        I'm an example component.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            // Echo.private("events")
            //             .listen('RealTimeMessage', (e) => {
            //                 console.log("RealTimeMessageVue: " + e.message);
            //         })

            // Echo.private("App.Models.Admin.1")
            //             .notification((notify) => {
            //                 console.log("Notification: " + notify.message);
            //         })
        }
    }
</script>
