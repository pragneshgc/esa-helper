import { reactive, ref } from "vue";

export function useQueryGroup() {
  const queryGroups = ref([]);
  const groupCondition = ref("");
  const rule = reactive({
    condition: "",
    operator: "",
    field: "",
    value: "",
  });

  return {
    queryGroups,
    groupCondition,
    rule,
  };
}
