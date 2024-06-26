import AddIcon from "@mui/icons-material/Add";
import CancelIcon from "@mui/icons-material/Close";
import DeleteOutlinedIcon from "@mui/icons-material/DeleteOutlined";
import EditOutlinedIcon from "@mui/icons-material/EditOutlined";
import InfoOutlinedIcon from "@mui/icons-material/InfoOutlined";
import SaveIcon from "@mui/icons-material/Save";
import { Box, useTheme } from "@mui/material";
import Button from "@mui/material/Button";
import {
  DataGrid,
  GridActionsCellItem,
  GridRowEditStopReasons,
  GridRowModes,
} from "@mui/x-data-grid";
import * as React from "react";
import { useState } from "react";
import { useNavigate } from "react-router-dom";
import Header from "../../components/Header";
import { tokens } from "../../theme";

import { useDispatch, useSelector } from "react-redux";
import { deleteDomainsSuccess } from "../../redux/DomainSlice";

function EditToolbar(props) {
  const { setRows, setRowModesModel, rows, arrIds } = props;
  const theme = useTheme();
  const colors = tokens(theme.palette.mode);

  const dispatch = useDispatch();
  const handleClick = () => {
    const maxId = rows.reduce((max, row) => (row.id > max ? row.id : max), 0);
    console.log(maxId);
    const id = maxId + 1;
    setRows((oldRows) => [
      ...oldRows,
      { id, name: "", admin: "", minimum: 0, isNew: true },
    ]);
    setRowModesModel((oldModel) => ({
      ...oldModel,
      [id]: { mode: GridRowModes.Edit, fieldToFocus: "name" },
    }));
  };

  const handleDeleteAll = () => {
    console.log(rows);
    dispatch(deleteDomainsSuccess(arrIds));
  };

  return (
    <Box>
      <Header title="Домены" subtitle="Управление доменами" />
      <Button
        color="primary"
        startIcon={<AddIcon />}
        onClick={handleClick}
        sx={{
          margin: "20px 5px",
          border: "1px solid",
          color: colors.grey[100],
        }}
      >
        Добавить домен
      </Button>
      <Button
        color="primary"
        startIcon={<DeleteOutlinedIcon />}
        onClick={handleDeleteAll}
        sx={{
          margin: "20px 5px",
          border: "1px solid",
          color: colors.grey[100],
        }}
      >
        Удалить домены
      </Button>
    </Box>
  );
}

const Domain = () => {
  const [rowModesModel, setRowModesModel] = React.useState({});
  const { domains, isFetch } = useSelector((state) => state.domain);
  const [arrIds, setArrIds] = useState([]);
  const [rows, setRows] = React.useState([]);

  React.useEffect(() => {
    setRows(domains);
  }, [domains]); // Зависимость массива domains
  const navigate = useNavigate();

  const theme = useTheme();
  const colors = tokens(theme.palette.mode);

  const handleButtonClick = (id) => {
    const rowData = rows.find((row) => row.id === id);
    const domainId = rowData.id;
    const domainName = rowData.name;
    navigate(`/records/${id}`, { state: { domainId, domainName } });
  };

  const handleRowEditStop = (params, event) => {
    if (params.reason === GridRowEditStopReasons.rowFocusOut) {
      event.defaultMuiPrevented = true;
    }
  };

  const handleEditClick = (id) => () => {
    setRowModesModel({ ...rowModesModel, [id]: { mode: GridRowModes.Edit } });
  };

  const handleSaveClick = (id) => () => {
    setRowModesModel({ ...rowModesModel, [id]: { mode: GridRowModes.View } });
  };

  const handleDeleteClick = (id) => () => {
    setRows(rows.filter((row) => row.id !== id));
  };

  const handleCancelClick = (id) => () => {
    setRowModesModel({
      ...rowModesModel,
      [id]: { mode: GridRowModes.View, ignoreModifications: true },
    });

    const editedRow = rows.find((row) => row.id === id);
    if (editedRow.isNew) {
      setRows(rows.filter((row) => row.id !== id));
    }
  };

  const processRowUpdate = (newRow) => {
    const updatedRow = { ...newRow, isNew: false };
    setRows(rows.map((row) => (row.id === newRow.id ? updatedRow : row)));
    return updatedRow;
  };

  const handleRowModesModelChange = (newRowModesModel) => {
    setRowModesModel(newRowModesModel);
  };

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
            <GridActionsCellItem
              icon={<SaveIcon />}
              label="Save"
              sx={{
                color: "primary.main",
              }}
              onClick={handleSaveClick(id)}
            />,
            <GridActionsCellItem
              icon={<CancelIcon />}
              label="Cancel"
              className="textPrimary"
              onClick={handleCancelClick(id)}
              color="inherit"
            />,
          ];
        }

        return [
          <GridActionsCellItem
            icon={<InfoOutlinedIcon />}
            label="Records"
            onClick={() => handleButtonClick(id)}
            color="inherit"
          />,
          <GridActionsCellItem
            icon={<EditOutlinedIcon />}
            label="Edit"
            className="textPrimary"
            onClick={handleEditClick(id)}
            color="inherit"
          />,
          <GridActionsCellItem
            icon={<DeleteOutlinedIcon />}
            label="Delete"
            onClick={handleDeleteClick(id)}
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
        onRowModesModelChange={handleRowModesModelChange}
        onRowEditStop={handleRowEditStop}
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
