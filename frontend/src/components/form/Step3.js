import { useState } from "react";
import { TextInput } from "../common";
export const emailIsValid = (email) => {
  return /(.+)@(.+){2,}\.(.+){2,}/.test(email);
};

export const validateStep3Form = (formData, fieldName) => {
  if (!formData?.step3Form) {
    return { name: "email", error: "Email can not be empty" };
  }
  for (const name of ["email", "firstName", "secondName"]) {
    if (fieldName && fieldName !== name) {
      continue;
    }
    const upperName = name[0].toUpperCase() + name.substring(1);
    if (
      !formData?.step3Form[name] ||
      (formData?.step3Form &&
        Object.hasOwnProperty.call(formData?.step3Form, name))
    ) {
      const value = formData?.step3Form[name];
      if (!value || value.length < 3) {
        return {
          name,
          error: upperName + " can not be less than 3 characters",
        };
      }
      if (name === "email" && !emailIsValid(value)) {
        return { name: "email", error: "Email should be a valid email" };
      }
    }
  }
  if (fieldName) {
    return { name: fieldName, error: "" };
  }

  return { name: "email", error: "" };
};
export const Step3 = ({ formData, setFormData }) => {
  const [formError, setFormError] = useState({});

  const onChange = (e) => {
    const { name, value } = e.target;
    const newFormData = {
      ...formData,
      step3Form: {
        ...formData.step3Form,
        [name]: value,
      },
    };
    setFormData(newFormData);

    const { name: inputName, error } = validateStep3Form(newFormData, name);
    const newFormErrors = { ...formError, [inputName]: error };
    setFormError(newFormErrors);
  };

  return (
    <>
      <TextInput
        name="email"
        onChange={onChange}
        label="Email Address"
        value={formData?.step3Form?.email || ""}
        error={formError?.email}
      />
      <TextInput
        name="firstName"
        onChange={onChange}
        label="First Name"
        value={formData?.step3Form?.firstName || ""}
        error={formError?.firstName}
      />
      <TextInput
        name="secondName"
        onChange={onChange}
        value={formData?.step3Form?.secondName || ""}
        error={formError?.secondName}
        label="Second Name"
      />
      {/* <div class="mb-3">
        <label for="firstName" class="form-label">
          First Name
        </label>
        <input
          onChange={onChange}
          value={formData?.step3Form?.firstName}
          type="text"
          name="firstName"
          class="form-control"
          id="firstName"
        />
        {!!formError?.firstName && (
          <div id="firstNameHelp" class="form-text">
            {formError.firstName}
          </div>
        )}
      </div>
      <div class="mb-3">
        <label for="SecondName" class="form-label">
          Second Name
        </label>
        <input
          onChange={onChange}
          value={formData?.step3Form?.secondName}
          type="text"
          name="secondName"
          class="form-control"
          id="SecondName"
        />
        {!!formError?.secondName && (
          <div id="secondNameHelp" class="form-text">
            {formError.secondName}
          </div>
        )}
      </div> */}
    </>
  );
};
