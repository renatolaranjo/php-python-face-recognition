<template>
  <div>
    <form @submit.prevent="submit">
      <b-alert :show="error != ''" variant="danger">{{ error }}</b-alert>
      <div class="text-right">
        <b-spinner v-if="searching" label="Spinning"></b-spinner>
        <b-button type="submit" variant="success" :disabled="$v.$invalid">Save</b-button>
      </div>
      <div class="row mb-5">
        <div class="col">
          <div class="md-form">
            <input type="text" id="name" name="name" placeholder="Name" v-model="$v.name.$model" />
            <label for="name">Name</label>
          </div>
          <div v-if="$v.name.$dirty">
            <span class="text-danger" v-if="!$v.name.required">Name is required</span>
          </div>
        </div>
      </div>
      <div class="row mb-5">
        <div class="col-sm-7">
          <div class="md-form">
            <input
              type="text"
              id="email"
              name="email"
              placeholder="E-mail"
              v-model="$v.email.$model"
            />
            <label for="email">E-mail</label>
          </div>
          <div v-if="$v.email.$dirty">
            <span class="text-danger" v-if="!$v.email.required">E-mail is required</span>
            <span class="text-danger" v-if="!$v.email.email">Not a valid e-mail</span>
          </div>
        </div>
        <div class="col-sm-5">
          <div class="md-form">
            <input type="text" id="country" name="country" placeholder="Country" v-model="$v.country.$model" />
            <label for="country">Country</label>
          </div>
          <div v-if="$v.country.$dirty">
            <span class="text-danger" v-if="!$v.country.required">Country is required</span>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <b-form-group label="Choose picture source">
            <b-form-radio v-model="optionPicture" name="picture" value="camera">Camera</b-form-radio>
            <b-form-radio v-model="optionPicture" name="picture" value="file">File</b-form-radio>
          </b-form-group>
        </div>
      </div>
      <div class="row">
        <div v-if="optionPicture == 'camera'" class="col-sm-6">
          <b-button block variant="primary" @click="accessCamera()">{{ labelCamera }}</b-button>
          <video ref="player" class="player"></video>
        </div>
        <div v-if="optionPicture == 'file'" class="col-sm-6">
          <label for="file">File</label>
          <input @change="getFile" type="file" class="form-control-file" />
        </div>
        <div class="col-sm-6">
          <b-button
            v-if="optionPicture == 'camera'"
            block
            variant="success"
            :disabled="!enableCapture"
            @click="captureImage()"
          >Capture</b-button>
          <div class="snap-box">
            <canvas ref="snapshot"></canvas>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>
<script>
import { validationMixin } from "vuelidate";
import { required, email } from "vuelidate/lib/validators";
import api from "../services/api";
export default {
  name: "Register",
  mixins: [validationMixin],
  validations: {
    name: {
      required,
    },
    email: {
      required,
      email,
    },
    country: {
      required,
    },
    imgEncoded: {
      required,
    },
  },
  data() {
    return {
      name: String(""),
      email: String(""),
      country: String(""),
      imgEncoded: String(""),
      error: String(""),
      localStream: {},
      labelCamera: String("Turn On Camera"),
      enableCapture: Boolean(false),
      searching: Boolean(false),
      optionPicture: String("camera"),
    };
  },
  mounted() {},
  destroyed() {
    if (this.localStream.active) {
      this.localStream.getTracks()[0].stop();
    }
  },
  methods: {
    accessCamera: function () {
      const player = this.$refs.player;

      if (player.paused) {
        const handleSuccess = (stream) => {
          this.localStream = stream;
          player.srcObject = stream;
          player.play();
          this.labelCamera = "Turn Off Camera";
          this.enableCapture = true;
        };
        navigator.mediaDevices
          .getUserMedia({ video: true })
          .then(handleSuccess);
      } else {
        player.pause();
        player.src = "";
        this.localStream.getTracks()[0].stop();
        this.labelCamera = "Turn On Camera";
        this.enableCapture = false;
      }
    },
    captureImage: function () {
      let snapshotCanvas = this.$refs.snapshot;
      let context = snapshotCanvas.getContext("2d");
      const player = this.$refs.player;
      // Draw the video frame to the canvas.
      context.drawImage(
        player,
        0,
        0,
        snapshotCanvas.width,
        snapshotCanvas.height
      );
      this.imgEncoded = snapshotCanvas.toDataURL();
    },
    submit() {
      this.$v.$touch();
      let data = {};
      this.searching = true;
      data["name"] = this.name;
      data["email"] = this.email;
      data["country"] = this.country;
      data["encode_img"] = this.imgEncoded;
      if (!this.$v.$invalid) {
        api
          .post("register", data)
          .then((response) => {
            this.searching = false;
            this.flashSuccess("User saved", {
              timeout: 2000,
            });
            this.$router.push("/");
          })
          .catch((err) => {
            let errors = err.response.data.errors;
            let index = Object.keys(errors)[0];
            this.error = errors[index][0];
          });
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
form {
  margin: 2em 0;
}
.md-form {
  display: flex;
  flex-flow: column-reverse;
  margin-bottom: 1em;
}
label,
input {
  transition: all 0.2s;
  touch-action: manipulation;
}
input {
  font-size: 1.5em;
  border: 0;
  border-bottom: 1px solid #ccc;
  font-family: inherit;
  -webkit-appearance: none;
  border-radius: 0;
  padding: 0;
  cursor: text;
}
input:focus {
  outline-width: 0;
}
input:placeholder-shown + label {
  cursor: text;
  max-width: 66.66%;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  transform-origin: left bottom;
  transform: translate(0, 2.125rem) scale(1.5);
}
::-webkit-input-placeholder {
  opacity: 0;
  transition: inherit;
}

input:focus::-webkit-input-placeholder {
  opacity: 1;
}
input:not(:placeholder-shown) + label,
input:focus + label {
  transform: translate(0, 0) scale(1);
  cursor: pointer;
}
canvas {
  background-color: aliceblue;
  width: 100%;
  height: 396px;
}
.snap-box {
  padding-top: 5px;
}
.player {
  width: 100%;
  margin-top: 5px;
}
</style>