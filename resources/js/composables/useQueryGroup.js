import { computed, reactive, readonly, ref } from "vue";

const queryGroups = ref([]);
const rules = ref([]);

export function useQueryGroup() {
  const addQueryGroup = (groupId) => {
    queryGroups.value.push({
      id: groupId,
      rules: [],
      condition: "",
    });
  };

  const removeQueryGroup = (index) => {
    queryGroups.value = queryGroups.value.filter((group) => {
      return group.id !== index;
    });
  };

  const addRule = (groupindex, rule) => {
    queryGroups.value.filter((group) => {
      if (group.id === groupindex) {
        group.rules.push({
          id: rule,
          field: "",
          operator: "",
          value: "",
          condition: "",
        });
      }
    });

    rules.value.push(rule);
  };

  const updateRuleFilter = (groupindex, ruleindex, filter) => {
    queryGroups.value.filter((group) => {
      if (group.id === groupindex) {
        group.rules.filter((rule) => {
          if (rule.id === ruleindex) {
            rule.condition = filter.condition;
            rule.field = filter.field;
            rule.operator = filter.operator;
            rule.value = filter.value;
          }
        });
      }
    });
  };

  const removeRule = (groupindex, ruleindex) => {
    queryGroups.value.filter((group) => {
      if (group.id === groupindex) {
        group.rules = group.rules.filter((rule) => rule.id !== ruleindex);
      }
    });

    rules.value = rules.value.filter((rule) => rule !== ruleindex);
  };

  const findGroup = (groupindex) => {
    let groups = queryGroups.value.filter((group) => {
      if (group.id === groupindex) {
        return group;
      }
    });
    return groups;
  };

  const getGroupRules = (groupindex) => {
    let groups = queryGroups.value.filter((group) => {
      if (group.id === groupindex) {
        return group;
      }
    });
    return groups;
  };

  const setQueryGroups = (values) => {
    queryGroups.value = values;
  };

  return {
    queryGroups,
    rules,
    addQueryGroup,
    removeQueryGroup,
    addRule,
    removeRule,
    findGroup,
    getGroupRules,
    updateRuleFilter,
    setQueryGroups,
  };
}
