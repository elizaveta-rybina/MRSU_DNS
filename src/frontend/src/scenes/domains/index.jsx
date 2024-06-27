import DeleteOutlinedIcon from "@mui/icons-material/DeleteOutlined";
import EditOutlinedIcon from "@mui/icons-material/EditOutlined";
import InfoOutlinedIcon from "@mui/icons-material/InfoOutlined";
import { Box, useTheme } from "@mui/material";
import { DataGrid, GridActionsCellItem, GridRowModes } from "@mui/x-data-grid";
import { createSelector } from "@reduxjs/toolkit";
import * as React from "react";
import { useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import SaveCancelButtons from "../../components/Buttons/SaveCancelButtons";
import Header from "../../components/Header/Header";
import HeaderButtons from "../../components/Header/HeaderButtons";
import { deleteDomainsSuccess } from "../../redux/slices/DomainSlice";
import { tokens } from "../../theme";
import useHelpers from "../../utils/helpers";

function EditToolbar(props) {
  const { setRows, setRowModesModel, rows, arrIds } = props;

  const dispatch = useDispatch();
  const handleClick = () => {
    const maxId = rows.reduce((max, row) => (row.id > max ? row.id : max), 0);
    console.log(maxId);
    const id = maxId + 1;
    setRows((oldRows) => [
      ...oldRows,
      { id, name: "", admin: "", minimum: 0, isNew: true },
    ]);
    console.log(id);
    setRowModesModel((oldModel) => ({
      ...oldModel,
      [id]: { mode: GridRowModes.Edit, fieldToFocus: "name" },
    }));
  };

  const handleDeleteAll = () => {
    dispatch(deleteDomainsSuccess(arrIds));
  };

  return (
    <Box>
      <Header title="Домены" subtitle="Управление доменами" />
      <HeaderButtons
        addInscription="Добавить домен"
        deleteInscription="Удалить домен"
        handleClick={handleClick}
        handleDeleteAll={handleDeleteAll}
      />
    </Box>
  );
}

const domainSelector = createSelector(
  (state) => state.domain,
  (domain) => domain
);

const Domain = () => {
  const [rowModesModel, setRowModesModel] = React.useState({});
  const { domains } = useSelector(domainSelector);
  const [arrIds, setArrIds] = useState([]);
  const [rows, setRows] = React.useState([]);

  //TODO: спросить можно ли так много передавать параметров и можно ли сделать лучше (может через пропсы)

  const {
    infoClick,
    editClick,
    deleteClick,
    rowEditStop,
    saveClick,
    cancelClick,
    processRowUpdate,
    rowModesModelChange,
  } = useHelpers(rowModesModel, setRowModesModel, rows, setRows);

  React.useEffect(() => {
    setRows(domains);
  }, [domains]);

  const theme = useTheme();
  const colors = tokens(theme.palette.mode);

  const columns = [
    { field: "id", headerName: "ID" },
    {
      field: "name",
      headerName: "Имя домена",
      flex: 1,
      editable: true,
      cellClassName: "name-column--cell",
    },
    {
      field: "admin",
      headerName: "Владелец",
      type: "string",
      flex: 1,
      editable: true,
    },
    {
      field: "minimum",
      headerName: "Дата последнего изменения",
      type: "number",
      flex: 1,
      editable: true,
    },
    {
      field: "actions",
      type: "actions",
      headerName: "Действия",
      width: 100,
      cellClassName: "actions",
      getActions: ({ id }) => {
        const isInEditMode = rowModesModel[id]?.mode === GridRowModes.Edit;

        if (isInEditMode) {
          return [
            <SaveCancelButtons
              id={id}
              saveClick={saveClick}
              cancelClick={cancelClick}
            />,
          ];
        }

        return [
          <GridActionsCellItem
            icon={<InfoOutlinedIcon />}
            label="Records"
            onClick={() => infoClick(id)}
            color="inherit"
          />,
          <GridActionsCellItem
            icon={<EditOutlinedIcon />}
            label="Edit"
            className="textPrimary"
            onClick={() => editClick(id)}
            color="inherit"
          />,
          <GridActionsCellItem
            icon={<DeleteOutlinedIcon />}
            label="Delete"
            onClick={() => deleteClick(id)}
            color="inherit"
          />,
        ];
      },
    },
  ];

  return (
    <Box
      m="15px"
      sx={{
        "& .MuiDataGrid-root": {
          border: colors.goldAccent[300],
        },
        "& .actions": {
          color: "text.secondary",
        },
        "& .textPrimary": {
          color: "text.primary",
        },
        "& .MuiCheckbox-root": {
          color: `${colors.goldAccent[300]} !important`,
        },
      }}
    >
      <DataGrid
        checkboxSelection
        rows={rows}
        columns={columns}
        editMode="row"
        rowModesModel={rowModesModel}
        onRowModesModelChange={rowModesModelChange}
        onRowEditStop={rowEditStop}
        processRowUpdate={processRowUpdate}
        slots={{
          toolbar: EditToolbar,
        }}
        onRowSelectionModelChange={(ids) => {
          setArrIds(ids);
        }}
        slotProps={{
          toolbar: { setRows, setRowModesModel, rows, arrIds },
        }}
      />
    </Box>
  );
};

export default Domain;
