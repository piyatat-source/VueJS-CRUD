<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!--  Responsive
     ------------------------------------------------>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>VueJS CRUD App with PHP</title>

    <!--  BootStrap 5 CSS
     ------------------------------------------------>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5" id="crudApp">
        <br>
        <h3 align="center">CRUD APP using VUEJS & PHP</h3></h3>
        <hr><br>
        <div class="row">
            <div class="col-md-6">
                <h3 class="panel-title">Users Data</h3>
            </div>
            <div class="col-md-6" align="right">
                <input type="button" class="btn btn-success btn-xs" data-bs-toggle="modal" data-bs-target="#myModal" @click="openModal" value="Add">
            </div>
        </div>
        <br>
        <marquee>===== &#128227; using VueJS with PHP &#128227; =====</marquee>
        <div class="table-responsive" style="text-align: center">
            <table class="table table-bordered table-striped">
                <tr>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
                
                <tr v-for="row in allData">
                    <td>{{ row.first_name }}</td>
                    <td>{{ row.last_name }}</td>
                    <td>{{ row.email }}</td>
                    <td align="center">
                        <button type="button" name="edit" class="btn btn-primary btn-xs edit" data-bs-toggle="modal" data-bs-target="#myModal" @click="fetchData(row.id)">Edit</button>
                        <button type="button" name="delete" class="btn btn-danger btn-xs delete" data-bs-toggle="modal" data-bs-target="#myModal" @click="deleteData(row.id)">Delete</button>
                    </td>
                </tr>
            </table>
        </div>

        <div v-if="myModal" class="modal fade" id="myModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!---------- Modal header ---------->
                    <div class="modal-header">
                        <h4 class="modal-title">{{ dynamicTitle }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="myModal=false"></button>
                    </div>
                    <!---------- Modal Body ---------->
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="firstname">Firstname</label>
                            <input type="text" class="form-control" v-model="first_name"> 
                            <!-- v-model เพื่อรับเอาค่าจาก Input มา -->
                        </div>
                        <div class="form-group">
                            <label for="lastname">lastname</label>
                            <input type="text" class="form-control" v-model="last_name">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" v-model="email">
                        </div>
                    </div>
                    <br>
                    
                    <div class="modal-footer">
                        <input type="hidden" v-model="hiddenId">
                        <input type="button" class="btn btn-success btn-xs" v-model="actionButton" @click="submitData">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    


<!--  BootStrap 5 JS
     ------------------------------------------------>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>

<!--  Vue.js
     ------------------------------------------------>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

<!--  Axios
 ------------------------------------------------>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<script>    

    let app = new Vue({
        el: '#crudApp', // Element <div id=crudApp>
        data: {
            allData: '',
            myModal: false,
            hiddenId: null,
            actionButton: 'Insert',
            dynamicTitle: 'Add data'
        },
        methods: {
            fetchAllData() {
                axios.post('action.php', {
                    action: 'fetchall'
                }).then(res => {
                    app.allData = res.data;
                }) 
            },
            openModal() {
                app.first_name = '';
                app.last_name = '';
                app.email = '';
                app.actionButton = 'Insert';
                app.dynamicTitle = 'Add Data';
                app.myModal = true; // เปิด Modal ขึ้นมา
            },
            submitData() {
                if (app.first_name != '' && app.last_name != '' && app.email != '') {
                    if (app.actionButton == 'Insert') {
                        axios.post('action.php', {
                            action: 'insert',
                            firstName: app.first_name,
                            lastName: app.last_name,
                            email: app.email,
                        }).then(res => { // หลังจากเพิ่มข้อมูล
                            app.myModal = false; // ปิด Modal
                            app.fetchAllData(); // แสดงข้อมูลทั้งหมดที่ตาราง
                            app.first_name = ''; // เซ็ต Input ของ first_name เป็นค่าว่าง
                            app.last_name = ''; // เซ็ต Input ของ last_name เป็นค่าว่าง
                            app.email = ''; // เซ็ต Input ของ email เป็นค่าว่าง
                            alert(res.data.message); // ขึ้นแจ้งเตือน
                            window.location.reload(); // รีโหลดหน้าเว็บ
                        })
                    }

                    if (app.actionButton == 'Update') {
                        axios.post('action.php', {
                            action: 'update',
                            firstName: app.first_name,
                            lastName: app.last_name,
                            email: app.email,
                            hiddenId: app.hiddenId,
                        }).then(res => { // หลังจากแก้ไขข้อมูล
                            app.myModal = false; // ปิด Modal
                            app.fetchAllData(); // แสดงข้อมูลทั้งหมดที่ตาราง
                            app.first_name = ''; // เซ็ต Input ของ first_name เป็นค่าว่าง
                            app.last_name = ''; // เซ็ต Input ของ last_name เป็นค่าว่าง
                            app.email = ''; // เซ็ต Input ของ email เป็นค่าว่าง
                            app.hiddenId = '';
                            alert(res.data.message); // ขึ้นแจ้งเตือน
                            window.location.reload(); // รีโหลดหน้าเว็บ
                        })
                    }
                }
            },
            fetchData(id) {
                axios.post('action.php', {
                    action: 'fetchSingle',
                    id: id
                }).then(res => {
                    app.first_name = res.data.first_name;
                    app.last_name = res.data.last_name;
                    app.email = res.data.email;
                    app.hiddenId = res.data.id;
                    app.myModal = true;
                    app.actionButton = 'Update';
                    app.dynamicTitle = 'Edit Data';
                })
            },
            deleteData(id) {
                if (confirm("Are you sure you want to remove this data?")) {
                    axios.post('action.php', {
                        action: 'delete',
                        id: id
                    }).then(res => {
                        app.fetchAllData();
                        alert(res.data.message);
                    })
                }
            }
        }, 
        created() {
            this.fetchAllData();
        }
    })

</script>
</body>
</html>