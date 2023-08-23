<template>
  <div>
    <div class="row">
      <div class="col">
        <b-alert :show="errorServer != ''" variant="danger">{{ errorServer }}</b-alert>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-3">
        <b-form-group label="Choose picture source">
          <b-form-radio v-model="optionPicture" name="picture" value="camera">Camera</b-form-radio>
          <b-form-radio v-model="optionPicture" name="picture" value="file">File</b-form-radio>
        </b-form-group>
      </div>
      <div class="col-sm-3 text-right">
        <canvas ref="snapshot" id="snapshot"></canvas>
      </div>
      <div class="col-sm-6">
        <div class="text-right">
          <b-button variant="success" :disabled="!enableRecon" @click="recog">Recognize</b-button>
        </div>
      </div>
    </div>
    <div class="row mt-1">
      <div v-if="optionPicture == 'camera'" class="col-sm-12 col-md-6">
        <b-button block variant="primary" @click="accessCamera()">{{ labelCamera }}</b-button>
        <video ref="player" class="player"></video>
      </div>
      <div v-if="optionPicture == 'file'" class="col-sm-12 col-md-6">
        <label for="file">File</label>
        <input @change="getFile" type="file" class="form-control-file mb-3" />
      </div>
      <div class="col-sm-12 col-md-6">
        <h4>Result</h4>
        <b-spinner v-if="searching" label="Spinning"></b-spinner>
        <table ref="table" class="table table-striped">
          <transition name="fade">
            <tbody v-if="found">
              <tr>
                <th scope="row">Name</th>
                <td v-html="name"></td>
              </tr>
              <tr>
                <th scope="row">E-mail</th>
                <td v-html="email"></td>
              </tr>
              <tr>
                <th scope="row">Country</th>
                <td v-html="country"></td>
              </tr>
              <tr>
                <th scope="row">Confidence</th>
                <td v-html="confidence"></td>
              </tr>
            </tbody>
          </transition>
        </table>
        <b-alert
          variant="warning"
          :show="!found && !searching && errorMessage != ''"
        >{{ errorMessage }}</b-alert>
      </div>
    </div>
  </div>
</template>
<script>
import api from "../services/api";
export default {
  name: "Identify",
  data() {
    return {
      name: String(""),
      email: String(""),
      country: String(""),
      imgEncoded: String(""),
      found: Boolean(false),
      confidence: String(""),
      searching: Boolean(false),
      errorMessage: String(""),
      localStream: {},
      labelCamera: String("Turn On Camera"),
      enableRecon: Boolean(false),
      optionPicture: String("camera"),
      errorServer: String(""),
    };
  },
  destroyed() {
    if (this.localStream.active) {
      this.localStream.getTracks()[0].stop();
    }
  },
  methods: {
    recog: function () {
      this.found = false;
      this.searching = true;
      let snapshotCanvas = this.$refs.snapshot;
      if (this.optionPicture == "camera") {
        let context = snapshotCanvas.getContext("2d");
        const player = this.$refs.player;
        context.drawImage(
          player,
          0,
          0,
          snapshotCanvas.width,
          snapshotCanvas.height
        );
      }
      let imgEncoded = snapshotCanvas.toDataURL();
      api
        .post("recog", { img: imgEncoded })
        .then((response) => {
          let data = response.data;
          if (data.status == "success") {
            this.name = data.user.name;
            this.email = data.user.email;
            this.country = data.user.country;
            this.confidence = data.confidence.toFixed(2) + "%";
            this.found = true;
            this.searching = false;
          } else {
            if (data.status == "unknown") {
              this.errorMessage = "No user was found.";
            } else {
              this.errorMessage = "No face was found.";
            }
            this.searching = false;
            this.found = false;
          }
        })
        .catch((err) => {
          let response = err.response;
          this.errorServer = response.status + " | " + response.statusText;
          this.searching = false;
        });
    },
    accessCamera: function () {
      const player = this.$refs.player;
      if (player.paused) {
        const handleSuccess = (stream) => {
          this.localStream = stream;
          player.srcObject = stream;
          player.play();
          this.labelCamera = "Turn Off Camera";
          this.enableRecon = true;
        };
        navigator.mediaDevices
          .getUserMedia({ video: true })
          .then(handleSuccess);
      } else {
        player.pause();
        player.src = "";
        this.localStream.getTracks()[0].stop();
        this.labelCamera = "Turn On Camera";
        this.enableRecon = false;
      }
    },
    getFile: function (event) {
      let file = event.target.files[0];
      var canvas = this.$refs.snapshot;
      if (!file.type.match("image.*")) {
        return;
      }
      let reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = function (e) {
        let encodedImage = e.target.result;
        this.imgEncoded = encodedImage;
        this.enableRecon = true;
        let image = new Image();
        image.src = encodedImage;
        image.onload = function (ev) {
          canvas.width = image.width;
          canvas.height = image.height;
          let ctx = canvas.getContext("2d");
          ctx.drawImage(image, 0, 0);
        };
      }.bind(this);
    },
  },
};
</script>
<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
.player {
  width: 100%;
  margin-top: 5px;
}
canvas {
  background-color: aliceblue;
  width: 50%;
  height: 100%;
}
</style>