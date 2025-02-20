<script setup>
import { watchEffect } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  student: Object,
  assessment: Object,
});

const gradeableTypes = ['PassFailGrade', 'NumericGrade', 'TEGrade'];

const teGradeResultOptions = ['TEA', 'TEP', 'TED'];
const passFailGradeResultOptions = [true, false];

const form = useForm({
  student_id: props.student?.id || '',
  assessment_id: props.assessment?.id || '',
  value: '',
  comment: '',
  gradeable_type: 'PassFailGrade',
});

watchEffect(() => {
  if (props.student && props.assessment) {
    form.student_id = props.student.id;
    form.assessment_id = props.assessment.id;
  }
});

const saveGrade = () => {
  form.post('/gradeables', {
    onSuccess: () => {
      form.reset();
    },
  });
};
</script>

<template>
  <form @submit.prevent="saveGrade">
    <div class="row g-3 mb-3">
      <div class="col-6">
        <label for="student_id" class="visually-hidden">Student</label>
        <input
          id="student_id"
          type="text"
          :value="props.student?.last_name || ''"
          disabled
          class="form-control"
        />
      </div>
      <div class="col-6">
        <label for="assessment_id" class="visually-hidden"
          >Assessment exercise</label
        >
        <input
          id="assessment_id"
          type="text"
          :value="props.assessment?.exercise.title || ''"
          disabled
          class="form-control"
        />
      </div>

      <!-- Dropdown for selecting gradeable type -->
      <div class="col-6">
        <label for="gradeable_type" class="visually-hidden"
          >Gradeable Type</label
        >
        <select
          id="gradeable_type"
          v-model="form.gradeable_type"
          class="form-control"
        >
          <option v-for="type in gradeableTypes" :key="type" :value="type">
            {{ type }}
          </option>
        </select>
      </div>

      <!-- TE Grade -->
      <div v-if="form.gradeable_type === 'TEGrade'" class="col-6">
        <label for="score" class="visually-hidden">Result</label>
        <div
          v-for="option in teGradeResultOptions"
          :key="option"
          class="form-check"
        >
          <input
            :id="`value-${option}`"
            v-model="form.value"
            class="form-check-input"
            type="radio"
            name="value"
            :value="option"
          />
          <label class="form-check-label" :for="`value-${option}`">
            {{ option }}
          </label>
        </div>
      </div>

      <!-- Pass/Fail -->
      <div v-if="form.gradeable_type === 'PassFailGrade'" class="col-6">
        <label for="score" class="visually-hidden">Result</label>
        <div
          v-for="option in passFailGradeResultOptions"
          :key="option"
          class="form-check"
        >
          <input
            :id="`value-${option}`"
            v-model="form.value"
            class="form-check-input"
            type="radio"
            name="value"
            :value="option"
          />
          <label class="form-check-label" :for="`value-${option}`">
            {{ option ? 'Passed' : 'Failed' }}
          </label>
        </div>
      </div>

      <!-- Numeric Grade -->
      <div v-if="form.gradeable_type === 'NumericGrade'" class="col-6">
        <label for="score" class="visually-hidden">Result</label>
        <input
          id="score"
          v-model="form.value"
          type="number"
          class="form-control"
          placeholder="Grade"
          min="0"
          max="10"
        />
      </div>

      <div class="col-12">
        <label for="comment" class="visually-hidden">Comment</label>
        <input
          id="comment"
          v-model="form.comment"
          type="text"
          class="form-control"
          placeholder="Comment"
        />
      </div>

      <div class="col-12">
        <button type="submit" class="btn btn-primary text-white w-variant">
          <i class="ri-add-line"></i>
          <span class="d-inline d-lg-none">Submit</span>
        </button>
      </div>
    </div>

    <ul v-if="form.errors">
      <li v-for="error in form.errors" :key="error" class="text-danger">
        {{ error }}
      </li>
    </ul>
  </form>
</template>
